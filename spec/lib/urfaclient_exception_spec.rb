require "urfaclient_exception"
require "rspec/its"

describe UrfaclientException do
  subject(:exception) { UrfaclientException.new }

  it 'should always hide its backtrace' do
    exception.set_backtrace 6.times.map{ "Some backtrace" }
    expect(exception.backtrace).to be_empty
  end
end
