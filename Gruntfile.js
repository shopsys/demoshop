module.exports = function(grunt) {

    require('es6-promise').polyfill();
    require('jit-grunt')(grunt, {
        sprite: 'pngsmith'
    });
    require('time-grunt')(grunt);

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        less: {
            helpersAdmin: {
                files: {
                    '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers/helpers-generated.less': '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers.less'
                }
            },
            admin: {
                files: {
                    'web/assets/admin/styles/index_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/admin/main.less'
                },
                options: {
                    compress: true,
                    sourceMap: true,
                    sourceMapFilename: 'web/assets/admin/styles/index_1588069177.css.map',
                    sourceMapBasepath: 'web',
                    sourceMapURL: 'index_1588069177.css.map',
                    sourceMapRootpath: '../../../',

                    modifyVars: {
                        frameworkResourcesDirectory: '"/var/www/html/app/../vendor/shopsys/framework/src/Resources"',
                    }
                }
            },
            styleguide: {
                files: {
                    'web/assets/styleguide/styles/styleguide_1588069177.css': '\/var\/www\/html\/app\/..\/src\/Shopsys\/ShopBundle\/Resources/styles/styleguide/main.less'
                },
                options: {
                    compress: true
                }
            },

            helpers1: {
                    files: {
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers/helpers-generated.less': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers.less'
                    }
                },
                frontend1: {
                    files: {
                        'web/assets/frontend/styles/index1_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/main.less'
                    },
                    options: {
                        compress: true,
                        sourceMap: true,
                        sourceMapFilename: 'web/assets/frontend/styles/index1_1588069177.css.map',
                        sourceMapBasepath: 'web',
                        sourceMapURL: 'index1_1588069177.css.map',
                        sourceMapRootpath: '../../../'
                    }
                },
                print1: {
                    files: {
                        'web/assets/frontend/styles/print1_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/print/main.less'
                    },
                    options: {
                        compress: true
                    }
                },
            helpers2: {
                    files: {
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/helpers-generated.less': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers.less'
                    }
                },
                frontend2: {
                    files: {
                        'web/assets/frontend/styles/index2_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/main.less'
                    },
                    options: {
                        compress: true,
                        sourceMap: true,
                        sourceMapFilename: 'web/assets/frontend/styles/index2_1588069177.css.map',
                        sourceMapBasepath: 'web',
                        sourceMapURL: 'index2_1588069177.css.map',
                        sourceMapRootpath: '../../../'
                    }
                },
                print2: {
                    files: {
                        'web/assets/frontend/styles/print2_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/print/main.less'
                    },
                    options: {
                        compress: true
                    }
                },
            helpers3: {
                    files: {
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/helpers-generated.less': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers.less'
                    }
                },
                frontend3: {
                    files: {
                        'web/assets/frontend/styles/index3_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/main.less'
                    },
                    options: {
                        compress: true,
                        sourceMap: true,
                        sourceMapFilename: 'web/assets/frontend/styles/index3_1588069177.css.map',
                        sourceMapBasepath: 'web',
                        sourceMapURL: 'index3_1588069177.css.map',
                        sourceMapRootpath: '../../../'
                    }
                },
                print3: {
                    files: {
                        'web/assets/frontend/styles/print3_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/print/main.less'
                    },
                    options: {
                        compress: true
                    }
                },
            wysiwyg1: {
                    files: {
                        'web/assets/admin/styles/wysiwyg_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/wysiwyg.less'
                    },
                    options: {
                        compress: true
                    }
                },
            wysiwyg2: {
                    files: {
                        'web/assets/admin/styles/wysiwyg_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/wysiwyg.less'
                    },
                    options: {
                        compress: true
                    }
                },
            wysiwyg3: {
                    files: {
                        'web/assets/admin/styles/wysiwyg_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/wysiwyg.less'
                    },
                    options: {
                        compress: true
                    }
                },
            wysiwygLocalized1: {
                    files: {
                        'web/assets/admin/styles/wysiwyg-localized_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/admin//wysiwyg-localized.less'
                    },
                    options: {
                        compress: true
                    }
                },            wysiwygLocalized2: {
                    files: {
                        'web/assets/admin/styles/wysiwyg-localized_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/admin//wysiwyg-localized.less'
                    },
                    options: {
                        compress: true
                    }
                },            wysiwygLocalized3: {
                    files: {
                        'web/assets/admin/styles/wysiwyg-localized_1588069177.css': '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/admin//wysiwyg-localized.less'
                    },
                    options: {
                        compress: true
                    }
                }            },

        postcss: {
            options: {
                processors: [
                    require('autoprefixer')({browserlist: ['last 3 versions']})
                ]
            },
            dist: {
                src: ['web/assets/frontend/styles/*.css', 'web/assets/admin/styles/*.css']
            }
        },

        sprite: {
            admin: {
                src: 'web/assets/admin/images/icons/*.png',
                dest: 'web/assets/admin/images/sprites/sprite.png',
                destCss: '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/libs/sprites.less',
                imgPath: '../images/sprites/sprite.png?v=' + (new Date().getTime()),
                algorithm: 'binary-tree',
                padding: 50,
                cssFormat: 'css',
                cssVarMap: function (sprite) {
                    sprite.name = 'sprite.sprite-' + sprite.name;
                },
                engineOpts: {
                    imagemagick: true
                },
                imgOpts: {
                    format: 'png',
                    quality: 90,
                    timeout: 10000
                },
                cssOpts: {
                    functions: false,
                    cssClass: function (item) {
                        return '.' + item.name;
                    },
                    cssSelector: function (sprite) {
                        return '.' + sprite.name;
                    }
                }
            },

            frontend: {
                src: 'web/assets/frontend/images/icons/*.png',
                dest: 'web/assets/frontend/images/sprites/sprite.png',
                destCss: '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/libs/sprites.less',
                imgPath: '../images/sprites/sprite.png?v=' + (new Date().getTime()),
                algorithm: 'binary-tree',
                padding: 50,
                cssFormat: 'css',
                cssVarMap: function (sprite) {
                    sprite.name = 'sprite.sprite-' + sprite.name;
                },
                engineOpts: {
                    imagemagick: true
                },
                imgOpts: {
                    format: 'png',
                    quality: 90,
                    timeout: 10000
                },
                cssOpts: {
                    functions: false,
                    cssClass: function (item) {
                        return '.' + item.name;
                    },
                    cssSelector: function (sprite) {
                        return '.' + sprite.name;
                    }
                }
            }
        },

        webfont: {
            admin: {
                src: '/var/www/html/app/../vendor/shopsys/framework/src/Resources/svg/admin/*.svg',
                dest: 'web/assets/admin/fonts',
                destCss: '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/libs/',
                options: {
                    autoHint: false,
                    font: 'svg',
                    hashes: true,
                    types: 'eot,woff,ttf,svg',
                    engine: 'node',
                    stylesheet: 'less',
                    relativeFontPath: '../fonts',
                    fontHeight: '512',
                    descent: '0',
                    destHtml: 'docs/generated',
                    htmlDemo: true,
                    htmlDemoTemplate: '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/views/Grunt/htmlDocumentTemplate.html',
                    htmlDemoFilename: 'webfont-admin-svg',
                    templateOptions: {
                        baseClass: 'svg',
                        classPrefix: 'svg-',
                        mixinPrefix: 'svg-'
                    }
                }
            },
            frontend: {
                src: '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/svg/front/*.svg',
                dest: 'web/assets/frontend/fonts',
                destCss: '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/libs/',
                options: {
                    autoHint: false,
                    font: 'svg',
                    hashes: true,
                    types: 'eot,woff,ttf,svg',
                    engine: 'node',
                    stylesheet: 'less',
                    relativeFontPath: '../fonts',
                    fontHeight: '512',
                    descent: '0',
                    destHtml: 'docs/generated',
                    htmlDemo: true,
                    htmlDemoTemplate: '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/views/Grunt/htmlDocumentTemplate.html',
                    htmlDemoFilename: 'webfont-frontend-svg',
                    templateOptions: {
                        baseClass: 'svg',
                        classPrefix: 'svg-',
                        mixinPrefix: 'svg-'
                    }
                }
            }
        },

        watch: {
            admin: {
                files: [
                    '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/**/*.less',
                    '!/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers.less',
                    '!/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers/*.less',
                    '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers/helpers-generated.less'
                ],
                tasks: ['adminLess']
            },
            adminHelpers: {
                files: [
                    '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers.less',
                    '/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers/*.less',
                    '!/var/www/html/app/../vendor/shopsys/framework/src/Resources/styles/admin/helpers/helpers-generated.less'
                ],
                tasks: ['less:helpersAdmin']
            },
            adminSprite: {
                files: ['web/assets/admin/images/icons/**/*.png'],
                tasks: ['sprite:admin'],
                options: {
                    livereload: true
                }
            },
            adminWebfont: {
                files: ['/var/www/html/app/../src/Shopsys/ShopBundle/Resources/svg/admin/*.svg'],
                tasks: ['webfont:admin'],
                options: {
                    livereload: true
                }
            },
            frontendSprite: {
                files: ['web/assets/frontend/images/icons/**/*.png'],
                tasks: ['sprite:frontend'],
                options: {
                    livereload: true
                }
            },
            frontendWebfont: {
                files: ['/var/www/html/app/../src/Shopsys/ShopBundle/Resources/svg/front/*.svg'],
                tasks: ['webfont:frontend']
            },
            frontend1: {
                    files: [
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/**/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers/*.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers/helpers-generated.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/core/mixin/*.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/core/mixin/base.less'
                    ],
                    tasks:['frontendLess1','frontendLess2','frontendLess3']
                    },
                helpers1: {
                    files: [
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/helpers/helpers-generated.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/core/mixin/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/common/core/mixin/base.less'
                    ],
                    tasks:['less:helpers1','less:helpers2','less:helpers3']
                    },
            frontend2: {
                    files: [
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/**/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/*.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/helpers-generated.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/*.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/base.less'
                    ],
                    tasks:['frontendLess2']
                    },
                helpers2: {
                    files: [
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/helpers-generated.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/base.less'
                    ],
                    tasks:['frontendLess2']
                    },
            frontend3: {
                    files: [
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/**/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/*.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/helpers-generated.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/*.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/base.less'
                    ],
                    tasks:['frontendLess3']
                    },
                helpers3: {
                    files: [
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/helpers/helpers-generated.less',
                        '/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/*.less',
                        '!/var/www/html/app/../src/Shopsys/ShopBundle/Resources/styles/front/domain2/core/mixin/base.less'
                    ],
                    tasks:['frontendLess3']
                    },
            
            livereload: {
                options: {
                    livereload: true
                },
                files: ['web/assets/admin/styles/*.css', 'web/assets/frontend/styles/*.css']
            },
            twig: {
                files: ['/var/www/html/app/../src/Shopsys/ShopBundle/Resources/views/**/*.twig'],
                tasks: [],
                options: {
                    livereload: true,
                }
            }
        }
    });
    grunt.loadNpmTasks('grunt-spritesmith');

    grunt.registerTask('default', ["sprite:admin", "sprite:frontend", "webfont", "less", "postcss"]);

    grunt.registerTask('frontend1', ['webfont:frontend', 'sprite:frontend', 'less:frontend1', 'less:print1', 'less:wysiwyg1'], 'postcss');
    grunt.registerTask('frontend2', ['webfont:frontend', 'sprite:frontend', 'less:frontend2', 'less:print2', 'less:wysiwyg2'], 'postcss');
    grunt.registerTask('frontend3', ['webfont:frontend', 'sprite:frontend', 'less:frontend3', 'less:print3', 'less:wysiwyg3'], 'postcss');
    grunt.registerTask('admin', ['sprite:admin', 'webfont:admin', 'less:admin']);

    grunt.registerTask('frontendLess1', ['less:frontend1', 'less:print1', 'less:wysiwyg1']);
    grunt.registerTask('frontendLess2', ['less:frontend2', 'less:print2', 'less:wysiwyg2']);
    grunt.registerTask('frontendLess3', ['less:frontend3', 'less:print3', 'less:wysiwyg3']);
    grunt.registerTask('adminLess', ['less:admin']);
};
