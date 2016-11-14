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
      code = @socket.recvfrom(2).unpack("s")[1]
      length = @socket.recvfrom(2).unpack("n")[1]
      tmp_len += length
      data = if (length == 4)
        nil
      else
        @socket.recvfrom(length - 4)
      end
      if code == 5
        @data << data
      else
        @attr[code]['data'] = data
        @attr[code]['len'] = length
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
    @socket.write @code.chr
    @socket.write VERSION.chr
    @socket.write [@len].pack("n")
    @attr.each do |code, value|
      @socket.write [code].pack("v")
      @socket.write [value['len']].pack("n")
      @socket.write value['data']
    end
    @data.each do |code, value|
      @socket.write [5].pack("v")
      @socket.write [value.size + 4].pack("n")
      @socket.write value
    end
  end

  private

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
