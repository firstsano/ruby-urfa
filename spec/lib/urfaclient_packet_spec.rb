require "urfaclient_packet"
require "rspec/its"


describe UrfaclientPacket do
  subject(:packet) { UrfaclientPacket.new }
  before(:all) do
    @connection_transformations = {
      int: {
        string: "test",
        value: 1952805748
      }
    }
  end

  it 'should unpack binary string to integer' do
    expect(packet.bin2int(@connection_transformations[:int][:string])).to eq(@connection_transformations[:int][:value])
  end
end
