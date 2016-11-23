require "urfaclient_packet"
require "urfaclient_connection"
require "rspec/its"

describe UrfaclientConnection do
  def new_connection(params = {})
    defaults = {
      address: "127.0.0.1",
      port: 80,
      login: "test",
      pass: "test"
    }

    UrfaclientConnection.new(**defaults.merge(params))
  end

  def mock_instantion_of(class_object)
    double_object = instance_double class_object
    allow(class_object).to receive(:new).and_return double_object
    double_object
  end

  def mocks_instantion_of(*class_objects)
    class_objects.map { |o| mock_instantion_of o }
  end

  context "on initialize" do
    after(:each) { new_connection }

    it 'should try to open connection' do
      expect_any_instance_of(UrfaclientConnection).to receive(:open)
    end

    it 'should raise error on unsuccessfull connection open' do
      allow_any_instance_of(UrfaclientConnection).to receive(:open).and_return(false)
      expect_any_instance_of(UrfaclientConnection).to receive(:connection_error)
    end

    context 'after opening connection' do
      before(:each) { allow_any_instance_of(UrfaclientConnection).to receive(:open).and_return(true) }

      it 'should try to open connection' do
        expect_any_instance_of(UrfaclientConnection).to receive(:login)
      end

      it 'should raise error on unsuccessful authorizing' do
        allow_any_instance_of(UrfaclientConnection).to receive(:login).and_return(false)
        expect_any_instance_of(UrfaclientConnection).to receive(:login_error)
      end
    end
  end

  context "methods" do
    before(:each) do
      @tcp_socket, @ssl_socket = mocks_instantion_of TCPSocket, OpenSSL::SSL::SSLSocket
      allow(@ssl_socket).to receive(:sync_close=)
      allow(@ssl_socket).to receive(:connect)
    end
    subject(:connection) { new_connection }

    describe "UrfaclientConnection#open" do
      it 'should generate ssl context' do
        expect_any_instance_of(UrfaclientConnection).to receive(:generate_ssl_context)
        connection
      end

      it 'should set up connection socket' do
        expect(connection.socket).not_to be_falsey
      end
    end
    #
    # describe "UrfaclientConnection#login" do
    #   it 'should call urfa_auth on packet authorication code' do
    #     allow(connection).to receive(:get_packet).and_return(packet)
    #     expect(connection).to receive(:urfa_auth)
    #     connection.login(auth.login, auth.pass)
    #   end
    # end
    #
    # describe "UrfaclientConnection#get_packet" do
    #   it 'should return UrfaclientPacket' do
    #     packet = connection.get_packet
    #     expect(packet.is_a?(UrfaclientPacket)).to be_truthy
    #   end
    # end
    #
    # describe "UrfaclientConnection#ssl_connect" do
    #   let (:connection_socket) { connection.socket }
    #
    #   it 'should enable crypto using connection socket' do
    #     expect(connection_socket).to respond_to(:ssl_version)
    #   end
    #
    #   it 'should open socket with correct ssl version' do
    #     expect(connection_socket.ssl_version).to eq(UrfaclientConnection::SSL_VERSION)
    #   end
    # end
    #
    # describe "UrfaclientConnection#urfa_call" do
    #   it 'should read and write to socket'
    # end
    #
    # describe "UrfaclientConnection#urfa_get_data" do
    #   it 'should read data from packet'
    # end
    #
    # describe "UrfaclientConnection#urfa_send_param" do
    #   it 'should write some data to packet'
    # end
    #
    # describe "UrfaclientConnection#close" do
    #   it 'should call close on socket' do
    #     expect(ssl_socket).to receive(:close)
    #     connection.close
    #   end
    # end
  end
end
