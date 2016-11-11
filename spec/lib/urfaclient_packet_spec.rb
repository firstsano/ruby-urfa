require "urfaclient_packet"
require "rspec/its"
require "recursive-open-struct"

describe UrfaclientPacket do
  subject(:packet) { UrfaclientPacket.new }

  it 'should have attributes with default values' do
    expect(packet).to have_attributes(iterator: 0, attr: {}, sock: false, data: [])
    expect(packet).to respond_to(:code, :len)
  end

  describe "methods" do
    let(:urfaclient) do
      RecursiveOpenStruct.new({
        string: {
          value: "test str",
          unpacked_value: "test",
          to_integer: 1_952_805_748,
          to_double: 4.914662893768959e+252,
          to_long: 8_387_236_823_645_254_770
        },
        code: {
          value: rand(100),
        },
        ip: {
          value: "10.0.2.15",
          to_long: "\n\x00\x02\x0F"
        },
        offset: {
          string: 4,
          integer: 8,
          long: 8,
          double: 12
        }
      })
    end

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
        expect(packet).to have_attributes(code: 0, len: 4, iterator: 0, attr: {}, data: [])
      end
    end

    describe "UrfaclientPacket#bin2int" do
      it 'should unpack binary string to integer' do
        integer = packet.bin2int urfaclient.string.value
        expect(integer).to eq(urfaclient.string.to_integer)
      end
    end

    describe "UrfaclientPacket#bin2double" do
      it 'should unpack binary string to double/float' do
        double = packet.bin2double urfaclient.string.value
        expect(double).to eq(urfaclient.string.to_double)
      end
    end

    describe "UrfaclientPacket#bin2long" do
      it 'should unpack binary string to long' do
        long = packet.bin2long urfaclient.string.value
        expect(long).to eq(urfaclient.string.to_long)
      end
    end

    describe "UrfaclientPacket#data_set_string" do
      it 'should push string to data and increment length' do
        packet.data_set_string urfaclient.string.value
        expect(packet.data.first).to eq(urfaclient.string.value)
        expect(packet.len).to eq(urfaclient.string.value.length + urfaclient.offset.string)
      end
    end

    describe "UrfaclientPacket#data_get_string" do
      it 'should get string from data' do
        packet.data_set_string urfaclient.string.value
        expect(packet.data_get_string).to eq(urfaclient.string.value)
      end
    end

    describe "UrfaclientPacket#data_set_double" do
      it 'should push double as string to data and increment length' do
        packet.data_set_double urfaclient.string.to_double
        expect(packet.data.first).to eq(urfaclient.string.value)
        expect(packet.len).to eq(urfaclient.offset.double)
      end
    end

    describe "UrfaclientPacket#data_get_double" do
      it 'should get double from data' do
        packet.data_set_double urfaclient.string.to_double
        expect(packet.data_get_double).to eq(urfaclient.string.to_double)
      end
    end

    describe "UrfaclientPacket#data_set_ip_address" do
      it 'should push ip as long to data and increment length' do
        packet.data_set_ip_address urfaclient.ip.value
        expect(packet.data.first).to eq(urfaclient.ip.to_long)
        expect(packet.len).to eq(urfaclient.offset.long)
      end
    end

    describe "UrfaclientPacket#data_get_ip_address" do
      it 'should get ip from data' do
        packet.data_set_ip_address urfaclient.ip.value
        expect(packet.data_get_ip_address).to eq(urfaclient.ip.value)
      end
    end

    describe "UrfaclientPacket#data_set_int" do
      it 'should push integer as string and increment length' do
        packet.data_set_int urfaclient.string.to_integer
        expect(packet.data.first).to eq(urfaclient.string.unpacked_value)
        expect(packet.len).to eq(urfaclient.offset.integer)
      end
    end

    describe "UrfaclientPacket#data_get_int" do
      it 'should get integer from data' do
        packet.data_set_int urfaclient.string.to_integer
        expect(packet.data_get_int).to eq(urfaclient.string.to_integer)
      end
    end

    describe "UrfaclientPacket#data_get_long" do
      before(:example) do
        packet.data << urfaclient.string.value
      end
      it 'should get long from data' do
        expect(packet.data_get_long).to eq(urfaclient.string.to_long)
      end
    end

    describe "UrfaclientPacket#attr_get_int" do
      it 'should return false if no data in attr' do
        expect(packet.attr_get_int urfaclient.code.value).to be_falsey
      end

      it 'should get integer from attr and unpack it' do
        packet.attr[urfaclient.code.value]['data'] = urfaclient.string.value
        expect(packet.attr_get_int urfaclient.code.value).to eq(urfaclient.string.to_integer)
      end
    end

    describe "UrfaclientPacket#attr_set_int" do
      it 'should set data as binary string to attr and increment length' do
        packet.attr_set_int(urfaclient.string.to_integer, urfaclient.code.value)
        expect(packet.attr[urfaclient.code.value]['data']).to eq(urfaclient.string.unpacked_value)
        expect(packet.attr[urfaclient.code.value]['len']).to eq(urfaclient.offset.integer)
        expect(packet.len).to eq(urfaclient.offset.integer)
      end
    end

    describe "UrfaclientPacket#attr_set_string" do
      it 'should set data as string to attr and increment length' do
        packet.attr_set_int(urfaclient.string.value, urfaclient.code.value)
        expect(packet.attr[urfaclient.code.value]['data']).to eq(urfaclient.string.value)
        expect(packet.attr[urfaclient.code.value]['len']).to eq(urfaclient.offset.string)
        expect(packet.len).to eq(urfaclient.offset.string)
      end
    end
  end
end
