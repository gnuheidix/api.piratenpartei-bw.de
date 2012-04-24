#!/usr/bin/expect
# Creates database tables for each model. This file is used in travis-ci test setup.
foreach model {WikiPages WikiElements WikiImages} {
    spawn ./app/Console/cake schema create $model
    set input y
    expect "(y/n)"
    send "$input\r"
    expect "(y/n)"
    send "$input\r"
    expect eof
}
