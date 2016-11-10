require "ipaddr"

class UrfaclientPacket

  VERSION = 35
  attr_accessor :code, :len, :iterator, :attr, :sock, :data

  def initialize
    @iterator, @len, @attr, @sock, @data = 0, 0, [], false, []
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
    @code, @len, @iterator, @attr, @data = 0, 4, 0, [], []
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

  private

  def value_greater_than_32bit_integer?(value)
    (value & 0x80000000) != 0
  end

  def cut_32bit_integer(value)
    value & 0xFFFFFFFF
  end
end
