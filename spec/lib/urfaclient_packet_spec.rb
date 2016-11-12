require "urfaclient_packet"
require "rspec/its"
require "recursive-open-struct"

describe UrfaclientPacket do
  let(:socket) { Object.new }

  it 'should throw error on creation without socket' do
    expect{ UrfaclientPacket.new }.to raise_error(ArgumentError)
  end

  it 'should throw error on creation with empty socket' do
    expect{ UrfaclientPacket.new(nil) }.to raise_exception("wrong socket")
  end

  it 'should have attributes with default values' do
    packet = UrfaclientPacket.new socket
    expect(packet).to have_attributes(iterator: 0, attr: {}, sock: socket, data: [])
    expect(packet).to respond_to(:code, :len)
  end

  describe "methods" do
    subject(:packet) { UrfaclientPacket.new(socket) }
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

    describe "UrfaclientPacket#read" do
      context 'should read from socket and' do
        it 'should raise exception on version mismatch' do
          expect{ packet.read }.to raise_error
        end
        it 'should call parse_packet_data' do
          packet.parse_packet_data
          expect(packet).to receive(:parse_packet_data)
        end
      end
    end

    describe "UrfaclientPacket#write" do
      it 'should write to socket some data'
    end

    describe "UrfaclientPacket#parse_packet_data" do
      it 'should read and put some data to attr' do
        expect{ packet.parse_packet_data }.to change{ packet.attr }
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
      it 'should push string to data' do
        packet.data_set_string urfaclient.string.value
        expect(packet.data.first).to eq(urfaclient.string.value)
      end

      it 'should increment length' do
        expect{ packet.data_set_string urfaclient.string.value }.to change{ packet.len }.by(urfaclient.string.value.length + urfaclient.offset.string)
      end
    end

    describe "UrfaclientPacket#data_get_string" do
      it 'should get string from data' do
        packet.data_set_string urfaclient.string.value
        expect(packet.data_get_string).to eq(urfaclient.string.value)
      end
    end

    describe "UrfaclientPacket#data_set_double" do
      it 'should push double as string to data' do
        packet.data_set_double urfaclient.string.to_double
        expect(packet.data.first).to eq(urfaclient.string.value)
      end

      it 'should increment length' do
        expect{ packet.data_set_double urfaclient.string.to_double }.to change{ packet.len }.by(urfaclient.offset.double)
      end
    end

    describe "UrfaclientPacket#data_get_double" do
      it 'should get double from data' do
        packet.data_set_double urfaclient.string.to_double
        expect(packet.data_get_double).to eq(urfaclient.string.to_double)
      end
    end

    describe "UrfaclientPacket#data_set_ip_address" do
      it 'should push ip as long to data' do
        packet.data_set_ip_address urfaclient.ip.value
        expect(packet.data.first).to eq(urfaclient.ip.to_long)
      end

      it 'should increment length' do
        expect{ packet.data_set_ip_address urfaclient.ip.value }.to change{ packet.len }.by(urfaclient.offset.long)
      end
    end

    describe "UrfaclientPacket#data_get_ip_address" do
      it 'should get ip from data' do
        packet.data_set_ip_address urfaclient.ip.value
        expect(packet.data_get_ip_address).to eq(urfaclient.ip.value)
      end
    end

    describe "UrfaclientPacket#data_set_int" do
      it 'should push integer as string to data' do
        packet.data_set_int urfaclient.string.to_integer
        expect(packet.data.first).to eq(urfaclient.string.unpacked_value)
      end

      it 'should increment length' do
        expect{ packet.data_set_int urfaclient.string.to_integer }.to change{ packet.len }.by(urfaclient.offset.integer)
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
      it 'should set integer as binary string to attr' do
        packet.attr_set_int(urfaclient.string.to_integer, urfaclient.code.value)
        expect(packet.attr[urfaclient.code.value]['data']).to eq(urfaclient.string.unpacked_value)
        expect(packet.attr[urfaclient.code.value]['len']).to eq(urfaclient.offset.integer)
      end

      it 'should increment length' do
        expect{ packet.attr_set_int(urfaclient.string.to_integer, urfaclient.code.value) }.to change{ packet.len }.by(urfaclient.offset.integer)
      end
    end

    describe "UrfaclientPacket#attr_set_string" do
      it 'should set data as binary string to attr' do
        packet.attr_set_string(urfaclient.string.value, urfaclient.code.value)
        expect(packet.attr[urfaclient.code.value]['data']).to eq(urfaclient.string.value)
        expect(packet.attr[urfaclient.code.value]['len']).to eq(urfaclient.offset.string)
      end

      it 'should increment length' do
        expect{ packet.attr_set_string(urfaclient.string.value, urfaclient.code.value) }.to change{ packet.len }.by(urfaclient.offset.string)
      end
    end
  end
end
