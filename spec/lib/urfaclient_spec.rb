require 'urfaclient_connection'
require 'urfaclient'

describe Urfaclient do
  def urfaclient(params = {})
    defaults = {
      login:    "test",
      password: "test",
      admin:    false,
      address:  "127.0.0.1",
      port:     "11758",
      ssl:      true
    }

    Urfaclient.new(**defaults.merge(params))
  end

  context "on initilization" do
    it 'should call to creation of UrfaclientConnection instance' do
      expect(UrfaclientConnection).to receive(:new)
      urfaclient
    end
  end
end
