require "ipaddr"

class UrfaclientPacket

  VERSION = 35
  attr_accessor :code, :len, :iterator, :attr, :sock, :data

  def initialize(socket)
    clean
    @sock = socket
  end

  def bin2int(string)
    string.unpack("N").first
  end

  def bin2double(string)
    string.reverse.unpack("d").first
  end

  def bin2long(string)
    hi, lo = string.unpack("N2")
    cut_32bit_integer(lo) if value_greater_than_32bit_integer?(lo)
    if value_greater_than_32bit_integer?(hi)
      hi &= 0xFFFFFFFF
      hi ^= 0xFFFFFFFF
      lo ^= 0xFFFFFFFF
      lo -= 1;
      0 - hi * 4_294_967_296 - lo
    else
      hi * 4_294_967_296 + lo
    end
  end

  def clean
    @code, @len, @iterator, @data = 0, 4, 0, []
    @attr = attr_hash
  end

  def data_set_string(string)
    @data << string
    @len += string.length + 4
  end

  def data_get_string
    @iterator += 1
    @data[@iterator - 1]
  end

  def data_set_double(double)
    @data << [double].pack("d").reverse
    @len += 12
  end

  def data_get_double
    @iterator += 1
    bin2double @data[@iterator - 1]
  end

  def data_set_ip_address(ip)
    @data << [IPAddr.new(ip).to_i].pack("N")
    @len += 8
  end

  def data_get_ip_address
    @iterator += 1
    long2ip(bin2int(@data[@iterator - 1]) & 0xFFFFFFFF)
  end

  def data_set_int(integer)
    @data << [integer].pack("N")
    @len += 8
  end

  def data_get_int
    @iterator += 1
    bin2int @data[@iterator - 1]
  end

  def data_get_long
    @iterator += 1
    bin2long @data[@iterator - 1]
  end

  def attr_get_int(code)
    bin2int @attr[code]['data']
  rescue
    false
  end

  def attr_set_int(attribute, code)
    @attr[code]['data'] = [attribute].pack("N")
    @attr[code]['len'] = 8
    @len += 8
    self
  end

  def attr_set_string(attribute, code)
    @attr[code]['data'] = attribute
    @attr[code]['len'] = 4
    @len += 4
    self
  end

  def parse_packet_data
    tmp_len = 4
    while tmp_len < @len
      code, len = read_short_signed, read_long_number
      tmp_len += len
      data = if (len != 4)
        @sock.read(len - 4)
      end
      if code == 5
        @data << data
      else
        @attr[code]['data'] = data
        @attr[code]['len'] = len
      end
    end
  end

  def read
    @code = read_char_number
    if VERSION != read_char_number
      raise "Error code #{read_char_number}"
    else
      @len = read_long_number
      parse_packet_data
    end
  end

  def write
    write_char(@code, VERSION)
    write_short_unsigned_big_endian(@len)
    @attr.each do |code, value|
      write_short_unsigned_little_endian code
      write_short_unsigned_big_endian value['len']
      write_raw value['data']
    end
    @data.each do |value|
      write_short_unsigned_little_endian 5
      write_short_unsigned_big_endian (value.size + 4)
      write_raw value
    end
  end

  private

  def write_socket(value)
    @sock.write value
  end

  def write_raw(values)
    [values].flatten.each do |v|
      v = yield(v) if block_given?
      write_socket v
    end
  end

  def write_char(*values)
    write_raw(values) { |v| v.chr }
  end

  def write_short_unsigned_big_endian(*values)
    write_raw(values) { |v| [v].pack("n") }
  end

  def write_short_unsigned_little_endian(*values)
    write_raw(values) { |v| [v].pack("v") }
  end

  def read_short_signed
    @sock.read(2).unpack("s").second
  end

  def read_long_number
    @sock.read(2).unpack("n").second
  end

  def read_char_number
    @sock.read(1).ord
  end

  def long2ip(long)
    ip = []
    4.times do |i|
      ip.push(long.to_i & 255)
      long = long.to_i >> 8
    end
    ip.reverse.join(".")
  end

  def value_greater_than_32bit_integer?(value)
    (value & 0x80000000) != 0
  end

  def cut_32bit_integer(value)
    value & 0xFFFFFFFF
  end

  def attr_hash
    Hash.new { |h, k| h[k] = Hash.new(&h.default_proc) }
  end
end
