"use strict";
var page = require('webpage').create(),
    system = require('system'),
    address;
if (system.args.length === 1) {
    console.log('error');
    phantom.exit(1);
} else {
    address = system.args[1];
    // page.settings.userAgent='Mozilla/5.0 (Linux; Android 7.0; PRA-AL00 Build/HONORPRA-AL00; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/56.0.2924.87 Mobile Safari/537.36 Hexin_Gphone/9.49.03 (Royal Flush) hxtheme/0 innerversion/G037.08.256.1.32 userid/352918378';
    page.onResourceRequested = function (req) {

    };

    page.onResourceReceived = function (res) {
        if (res.url.indexOf(address) > -1 && res.status != 200) {
            if (res.status == 302 || res.status == 301) {
                address = res.redirectURL;
            } else {
                statusCode = res.status;
            }
        }
        if (res.stage == 'start') {

        }
        if (res.stage == 'start' && res.contentType == 'text/html') {

        }
    };
    page.open(address, function (status) {
        if (status !== 'success' || statusCode != 0) {
            console.log('-1');
            console.log(statusCode);
            phantom.exit(3);
        }
        phantom.exit(2);
    });
}
