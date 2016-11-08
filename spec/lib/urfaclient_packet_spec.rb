require "urfaclient_packet"
require "rspec/its"


describe UrfaclientPacket do
  subject(:packet) { UrfaclientPacket.new }
  before(:all) do
    @urfa_binary = {
      int: {
        string: "test",
        value: 1952805748
      },
      double: {
        string: "test string",
        value: 2.3556924101843323e+251
      },
      long: {
        string: "test string",
        value: 8387236823645254770
      }
    }
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
