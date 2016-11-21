require 'urfaclient_packet'
require 'openssl'

class UrfaclientConnection
  attr_accessor :error
  attr_reader :socket

  CERTIFICATE = 'admin.crt'
  PASSPHRASE = 'netup'

  def initialize(address, port, login, pass, ssl = true, admin = false)
    @admin = admin
    return connection_error unless open(address, port)
    return login_error unless login(login, pass, true)
  end

  def open(address, port)
    ssl_context = OpenSSL::SSL::SSLContext.new
    if @admin
      ssl_context.key = OpenSSL::PKey.read(CERTIFICATE, PASSPHRASE)
      ssl_context.verify_mode = VERIFY_PEER
    else
      ssl_context.ciphers = 'ADH-RC4-MD5'
    end
    tcp_socket = TCPSocket.open(address, port)
    @socket = OpenSSL::SSL::SSLSocket.new(tcp_socket, ssl_context)
    @socket.sync_close = true
    @socket.connect
    @socket.nil?
  end

  def login(login, pass, ssl = true)
    packet = get_packet
    until @socket.eof? do
      packet.clean
      packet.read
      case packet.code
      when 192
        urfa_auth(packet, login, pass, ssl)
      when 194
        ssl = packet.attr_get_int(10)
        ssl_connect(ssl) if ssl
        return true
      when 195
        return false
      end
    end
  end

  def get_packet
    UrfaclientPacket.new(@socket)
  end

  def urfa_auth(*args)
  end

  def close
    @socket.close
  end

  private

  def connection_error
    @error = 'connect error'
    false
  end

  def login_error
    @error = 'login error'
    false
  end
end
