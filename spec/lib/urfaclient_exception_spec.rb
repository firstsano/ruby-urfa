require "urfaclient_exception"
require "rspec/its"

describe UrfaclientException do
  its(:backtrace) { should be_empty }
end
