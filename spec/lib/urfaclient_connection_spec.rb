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
end
