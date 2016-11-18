require "urfaclient_connection"
require "rspec/its"

describe UrfaclientConnection do
  let(:auth) do
    OpenStruct.new({ address: "127.0.0.1", port: 80, login: "test", pass: "test" })
  end

  context "initialize behavior" do
    it 'should not allow to call initialize without arguments' do
      expect{ UrfaclientConnection.new }.to raise_error(ArgumentError)
    end

    it 'should set error and return false on unsuccessful opening' do
      expect_any_instance_of(UrfaclientConnection).to receive(:open).and_return(false)
      expect_any_instance_of(UrfaclientConnection).to receive(:connection_error)
      UrfaclientConnection.new(auth.address, auth.port, auth.login, auth.pass)
    end

    it 'should set error on unsuccessful logging' do
      expect_any_instance_of(UrfaclientConnection).to receive(:open).and_return(true)
      expect_any_instance_of(UrfaclientConnection).to receive(:login).and_return(false)
      expect_any_instance_of(UrfaclientConnection).to receive(:login_error)
      UrfaclientConnection.new(auth.address, auth.port, auth.login, auth.pass)
    end

    it 'should successfully create object whe no errors' do
      expect_any_instance_of(UrfaclientConnection).to receive(:open).and_return(true)
      expect_any_instance_of(UrfaclientConnection).to receive(:login).and_return(true)
      connection = UrfaclientConnection.new(auth.address, auth.port, auth.login, auth.pass)
      expect(connection).to be_an_instance_of(UrfaclientConnection)
    end
  end

  context "methods" do
    subject(:connection) { UrfaclientConnection.new(auth.address, auth.port, auth.login, auth.pass) }
    let(:socket) { instance_double(TCPSocket) }
    let(:ssl_socket) { double("OpenSSL::SSL::SSLSocket", :sync_close= => true, :connect => true) }

    describe "UrfaclientConnection#open" do
      it 'should initialize socket of connection' do
        allow(TCPSocket).to receive(:open).and_return socket
        allow(OpenSSL::SSL::SSLSocket).to receive(:new).and_return ssl_socket
        expect(ssl_socket).to receive(:connect)
        connection.open(auth.address, auth.port)
        expect(connection.socket).not_to be_nil
      end
    end

    describe "UrfaclientConnection#close" do
      it 'should call close on socket' do
        # expect(connection.socket).to receive(:close)
      end
    end
  end
end
