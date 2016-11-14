class UrfaclientConnection
  attr_accessor :error

  def initialize(address, port, login, pass, ssl = true, admin = false)
    @admin = admin
    return connection_error unless open(address, port)
    return login_error unless login(login, pass, true)
  end

  def open(*args)
  end

  def login(*args)
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
