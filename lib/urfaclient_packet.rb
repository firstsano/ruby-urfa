class UrfaclientPacket
  def bin2int(string)
    string.unpack("N*").first
  end
  def bin2double(string)
    string.reverse.unpack("d*").first
  end
end
