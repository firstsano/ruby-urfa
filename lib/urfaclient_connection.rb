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

  def ssl_connect
    ssl_context = OpenSSL::SSL::SSLContext.new(:SSLv23_client)
    @socket = OpenSSL::SSL::SSLSocket.new(@socket, ssl_context)
  end

  def urfa_call(code)
    packet = get_packet
    packet.clean
    packet.code = 201
    packet.attr_set_int code, 3
    packet.write
    unless @socket.eof?
      packet.clean
      packet.read
      case packet.code
      when 200
        return true if packet.attr_get_int(3) == code
        return false
      end
    end
  end

  def urfa_auth(packet, username, userpass, ssl)
    ssl = if @admin
      4
    else
      packet_data = packet.attr[6]['data']
      digest = OpenSSL::Digest::MD5.new
      digest << packet_data
      digest << userpass
      hash = digest.digest
      packet.clean
      packet.code = 193
      packet.attr_set_string(username, 2)
      packet.attr_set_string(packet_data, 8)
      packet.attr_set_string(hash, 9)
      packet.attr_set_int(2, 10)
      packet.attr_set_int(2, 1)
      packet.write
      2
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
