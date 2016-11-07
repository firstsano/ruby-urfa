class UrfaclientPacket
  def bin2int(string)
    string.unpack("N*").first
  end
end
