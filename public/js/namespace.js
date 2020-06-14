(function() {

    if (!window.hardkode) {
        window.hardkode = {};
    }

    window.namespace = function(ns, deps, factory) {

        let parts = ns.split('.');

        let parent = window.hardkode;

        if (parts[0] === 'hardkode') {
            parts = parts.slice(1);
        }

        for (let part of parts) {

            if (typeof parent[part] === "undefined") {
                parent[part] = {};
            }

            parent = parent[part];

        }

        if (!!parent.length) {
            throw new Error('Already declared.');
        }

        let dependencies = [];

        for (let dependency of deps) {
            dependencies.push(loadDependency(dependency));
        }

        Promise.all(dependencies).then(function(promises) {

            Object.assign(parent, factory.apply(factory, deps));
        }).catch(function(reason) {
            console.log(reason);
        });

    };

    const loadDependency = function(dependency) {

        return new Promise(function(resolve, reject) {

            let dependencyObj = eval(dependency);

            if (typeof dependencyObj === 'undefined') {

                return new Promise(function(resolve2, reject) {
                    resolve(setTimeout(function() {
                        console.log("waiting...", dependency);
                        loadDependency(dependency);
                    }, 10)
                    );
                });

            }

            return resolve(dependencyObj);

        });

    };

})();