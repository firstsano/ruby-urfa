require 'urfaclient_connection'
require 'urfaclient_packet'

class Urfaclient
  def initialize(login:, password:, admin:, address: "127.0.0.1", port: "11758", ssl: true)
    @address, @port = address, port
    @connection = UrfaclientConnection.new(
      login:    login,
      password: password,
      admin:    admin,
      address:  address,
      port:     port,
      ssl:      ssl
    )
    @error = @connection.error
  end
end
