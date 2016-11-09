require "urfaclient_packet"
require "rspec/its"


describe UrfaclientPacket do
  subject(:packet) { UrfaclientPacket.new }
  let(:packet_class) { UrfaclientPacket }
  before(:all) do
    @urfa_binary = {
      int: {
        string: "test",
        value: 1_952_805_748
      },
      double: {
        string: "test string",
        value: 2.3556924101843323e+251
      },
      long: {
        string: "test string",
        value: 8_387_236_823_645_254_770
      }
    }
  end

  it 'should have attributes with default values' do
    expect(packet_class).to have_attributes(version: 35, iterator: 0, attr: [], sock: false, data: [])
    expect(packet_class).to respond_to(:code, :len)
  end

  context 'unpacking binary string' do
    it 'can unpack to integer' do
      integer = packet.bin2int @urfa_binary[:int][:string]
      expect(integer).to eq(@urfa_binary[:int][:value])
    end
    it 'can unpack to double/float' do
      float = packet.bin2double @urfa_binary[:double][:string]
      expect(float).to eq(@urfa_binary[:double][:value])
    end
    it 'can unpack to long' do
      long = packet.bin2long @urfa_binary[:long][:string]
      expect(long).to eq(@urfa_binary[:long][:value])
    end
  end
end
