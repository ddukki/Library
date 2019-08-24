    var Ziggy = {
        namedRoutes: {"shelves.index":{"uri":"shelves","methods":["GET","HEAD"],"domain":null},"shelves.create":{"uri":"shelves\/create","methods":["GET","HEAD"],"domain":null},"shelves.store":{"uri":"shelves","methods":["POST"],"domain":null},"shelves.show":{"uri":"shelves\/{shelf}","methods":["GET","HEAD"],"domain":null},"shelves.edit":{"uri":"shelves\/{shelf}\/edit","methods":["GET","HEAD"],"domain":null},"shelves.update":{"uri":"shelves\/{shelf}","methods":["PUT","PATCH"],"domain":null},"shelves.destroy":{"uri":"shelves\/{shelf}","methods":["DELETE"],"domain":null},"shelves.add":{"uri":"shelves\/add","methods":["GET","HEAD"],"domain":null},"login":{"uri":"login","methods":["GET","HEAD"],"domain":null},"logout":{"uri":"logout","methods":["POST"],"domain":null},"register":{"uri":"register","methods":["GET","HEAD"],"domain":null},"password.request":{"uri":"password\/reset","methods":["GET","HEAD"],"domain":null},"password.email":{"uri":"password\/email","methods":["POST"],"domain":null},"password.reset":{"uri":"password\/reset\/{token}","methods":["GET","HEAD"],"domain":null},"password.update":{"uri":"password\/reset","methods":["POST"],"domain":null},"home":{"uri":"home","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'http://library.test/',
        baseProtocol: 'http',
        baseDomain: 'library.test',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
