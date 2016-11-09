require "urfaclient_packet"
require "rspec/its"


describe UrfaclientPacket do
  subject(:packet) { UrfaclientPacket.new }

  it 'should have attributes with default values' do
    expect(packet).to have_attributes(iterator: 0, attr: [], sock: false, data: [])
    expect(packet).to respond_to(:code, :len)
  end
end

context "methods" do
  subject(:packet) { UrfaclientPacket.new }
  let(:binary_string) { "test str" }
  let(:binary_integer)  { 1_952_805_748 }
  let(:binary_double)  { 4.914662893768959e+252 }
  let(:binary_long)  { 8_387_236_823_645_254_770 }
  let(:offset_string) { 4 }
  let(:offset_double) { 12 }

  describe "UrfaclientPacket#clean" do
    before(:example) do
      packet.code = rand(100)
      packet.len = rand(100)
      packet.iterator = rand(100)
      packet.attr = rand(100).times.map { rand(100) }
      packet.data = rand(100).times.map { rand(100) }
    end
    it 'should reset instance variables' do
      packet.clean
      expect(packet).to have_attributes(code: 0, len: 4, iterator: 0, attr: [], data: [])
    end
  end

  describe "UrfaclientPacket#bin2int" do
    it 'should unpack binary string to integer' do
      integer = packet.bin2int binary_string
      expect(integer).to eq(binary_integer)
    end
  end

  describe "UrfaclientPacket#bin2double" do
    it 'should unpack binary string to double/float' do
      float = packet.bin2double binary_string
      expect(float).to eq(binary_double)
    end
  end

  describe "UrfaclientPacket#bin2long" do
    it 'should unpack binary string to long' do
      long = packet.bin2long binary_string
      expect(long).to eq(binary_long)
    end
  end

  describe "UrfaclientPacket#data_set_string" do
    it 'should push string to data and increment length' do
      packet.data_set_string binary_string
      expect(packet.data.first).to eq(binary_string)
      expect(packet.len).to eq(binary_string.length + offset_string)
    end
  end

  describe "UrfaclientPacket#data_get_string" do
    it 'should get string from data' do
      packet.data_set_string binary_string
      expect(packet.data_get_string).to eq(binary_string)
    end
  end

  describe "UrfaclientPacket#data_set_double" do
    it 'should add double as string and increment length' do
      packet.data_set_double binary_double
      expect(packet.data.first).to eq(binary_string)
      expect(packet.len).to eq(offset_double)
    end
  end
end
