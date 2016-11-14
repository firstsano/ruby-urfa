require "urfaclient_connection"
require "rspec/its"

describe UrfaclientConnection do
  subject(:connection) { UrfaclientConnection.new }

  it 'should should respond to certain messages' do
    expect(connection).to respond_to(:error)
  end
end
