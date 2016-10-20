require "urfaclient_exception"

describe UrfaclientException do
  its(:backtrace) { should be_empty }
end
