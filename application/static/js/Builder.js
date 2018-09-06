var Builder = {
    includeFiles: function(files) {
        if (files instanceof Array) {
            for (var i = 0; i < files.length; i++) {
                this.includeJs(files[i]);
            }
        } else
            this.includeJs(files);
    },
    includeJs: function(filePath) {
        document.writeln('<script type="text/javascript" src="' + filePath + '.js?' + new Date().getTime() + '"></script>');
    },
    build: function(files) {
        this.includeFiles(files);
    }

};