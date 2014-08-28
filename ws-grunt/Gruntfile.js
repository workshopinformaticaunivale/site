module.exports = function(grunt) {

	var _frontConfig, _pluginsConfig, _themesConfig;
	//modules in usaged
	var	_modulesNames = 'concat|uglify|less|watch|usebanner'
	  , _gruntModules = {}
	  , _files        = false;

	//_frontConfig	= grunt.file.readJSON('front.json');
	_pluginsConfig	= grunt.file.readJSON('plugins.json');
	_themesConfig	= grunt.file.readJSON('themes.json');

	_modulesNames.split('|').forEach(function( module, index ){
		_gruntModules[ module ] = ( module != 'watch' ? { _none : {} } : {} ); /*is object empty*/
	});

	var _core = {
		getPathPluginsFront : function( control ) {
			var pathComplete = ( control.base + control.javascripts + 'plugins/' );
			return [ pathComplete + '*.front.js', pathComplete + '*.all.js' ];
		},

		getPathPluginsAdmin : function( control ) {
			var pathComplete = ( control.base + control.javascripts + 'plugins/' );
			return [ pathComplete + '*.admin.js', pathComplete + '*.all.js' ];
		},

		getPathPluginGenerate : function( control, nameFile ) {
			var pathComplete = ( control.base + control.javascripts + 'plugins/build/' + nameFile );
			return pathComplete;
		},

		getPathLibs : function( control ) {
			var pathComplete = ( control.base + control.javascripts + 'libs/*.js' );
			return [ pathComplete ];
		},

		getPathLibGenerate : function( control, nameFile ) {
			var pathComplete = ( control.base + control.javascripts + 'libs/build/' + nameFile );
			return pathComplete;
		},

		getPathScriptFront : function( control ) {
			var pathComplete = ( control.base + control.javascripts + '_front/' );

			return [
					'<%= concat.libs' + control.name  + '.dest %>',
					'<%= concat.pluginsFront' + control.name + '.dest %>',
					pathComplete + '_base/*.js' ,
					pathComplete + 'boot.js'
			];
		},

		getPathScriptAdmin : function( control ) {
			var pathComplete = ( control.base + control.javascripts + '_admin/' );

			return [
					'<%= concat.libs' + control.name  + '.dest %>',
					'<%= concat.pluginsAdmin' + control.name + '.dest %>',
					pathComplete + '_base/*.js' ,
					pathComplete + 'boot.js'
			];
		},

		getPathScript : function( control, nameFile ) {
			var pathComplete = ( control.base + control.javascripts + nameFile );
			return pathComplete;
		},

		initFiles : function() {
			_files = {};
		},

		unsetFiles : function() {
			_files = false;
		},

		runLayerProject : function( projectJson ) {

			projectJson.forEach(function( install, index ){
				var pluginsPath = install.base + install.javascripts + 'plugins/';
				var frontPath   = install.base + install.javascripts + '_front/';
				var adminPath   = install.base + install.javascripts + '_admin/';
				var _filesWatch = [];
				var _tasksWatch = [];

				//concat
				if ( install.isAdminJavascriptsControl || install.isFrontJavascriptsControl ) {
					_gruntModules.concat[ 'libs' + install.name ] = {
						options : {
							separator : ';\n'
						},
						src		: _core.getPathLibs( install ),
						dest 	: _core.getPathLibGenerate( install, 'libs.min.js' )
					}

					_core.initFiles();

					/*files watch*/
					_filesWatch.push( pluginsPath + '*.all.js' );

					_tasksWatch.push( 'concat:libs' + install.name );
					/*END*/
				}

				if ( install.isFrontJavascriptsControl ) {

					_gruntModules.concat[ 'pluginsFront' + install.name ] = {
						options : {
							separator : ';\n'
						},
						src		: _core.getPathPluginsFront( install ),
						dest 	: _core.getPathPluginGenerate( install, 'plugins.min.js' )
					}

					_gruntModules.concat[ 'front' + install.name ] = {
						options : {
							separator : ';\n'
						},
						src		: _core.getPathScriptFront( install ),
						dest 	: _core.getPathScript( install, 'script.min.js' )
					}

					_files[ _gruntModules.concat[ 'front' + install.name ].dest ] = _gruntModules.concat[ 'front' + install.name ].dest;

					/*files watch*/
					_filesWatch.push( pluginsPath + '*.front.js' );
					_filesWatch.push( frontPath   + '_base/*.js' );
					_filesWatch.push( frontPath   + 'boot.js' );

					_tasksWatch.push( 'concat:pluginsFront' + install.name );
					_tasksWatch.push( 'concat:front' + install.name );

					/*END*/
				}

				if ( install.isAdminJavascriptsControl ) {
					_gruntModules.concat[ 'pluginsAdmin' + install.name ] = {
						options : {
							separator : ';\n'
						},
						src		: _core.getPathPluginsAdmin( install ),
						dest 	: _core.getPathPluginGenerate( install, 'admin.plugins.min.js' )
					}

					_gruntModules.concat[ 'admin' + install.name ] = {
						options : {
							separator : ';\n'
						},
						src		: _core.getPathScriptAdmin( install ),
						dest 	: _core.getPathScript( install, 'admin.script.min.js' )
					}

					_files[ _gruntModules.concat[ 'admin' + install.name ].dest ] = _gruntModules.concat[ 'admin' + install.name ].dest;

					/*files watch*/
					_filesWatch.push( pluginsPath + '*.admin.js' );
					_filesWatch.push( adminPath   + '_base/*.js' );
					_filesWatch.push( adminPath   + 'boot.js' );

					_tasksWatch.push( 'concat:pluginsAdmin' + install.name );
					_tasksWatch.push( 'concat:admin' + install.name );

					/*END*/
				}
				/*END*/

				//uglify
				if ( _files ) {
					_gruntModules.uglify[ 'build' + install.name ] = {};
					_gruntModules.uglify[ 'build' + install.name ][ 'files' ] = _files;
				}
				/*END*/

				//less
				if ( install.isLessControl ) {
					_core.initFiles();

					_files[ install.base + 'style.css' ] = [ install.base + 'style.less' ];

					_gruntModules.less[ 'build' + install.name ] = {};
					_gruntModules.less[ 'build' + install.name ][ 'options' ] = { cleancss: true, strictMath: true, strictUnits: true,   };
					_gruntModules.less[ 'build' + install.name ][ 'files' ] = _files;

					/*user banner*/
					var bannerWordpress = ( install.header || {} );
					_gruntModules.usebanner[ 'wordpressHeader' + install.name ] = {
			  			options : {
			  				position  : 'top',
			  				banner    : '/* Theme Name: '  + bannerWordpress.name
			  				          + '\n Description: ' + bannerWordpress.description
			  				          + '\n Author: '      + bannerWordpress.author
			  				          + '\n Author URI: '  + bannerWordpress.authorUri
			  				          + '\n Version: '     + bannerWordpress.version  + '*/',
			  				linebreak : true
			  			},
			  			files: {
		       			 	src: [ install.base + 'style.css' ]
		      			}
	  				}

					_gruntModules.watch[ 'less' + install.name ] = {
						files : [ install.base + install.css + '*.less' ],
						tasks : [ 'less:build' + install.name, 'usebanner:wordpressHeader' + install.name ]
					}
				}
				/*END*/

				//watch
				if ( _filesWatch.length && _tasksWatch.length ) {
					//task Minified
					_tasksWatch.push( 'uglify:build' + install.name );

					_gruntModules.watch[ 'module' + install.name ] = {
						files : _filesWatch,
						tasks : _tasksWatch
					}
				}
				/*END*/
			});
		}
	}

	//Run layer project Front
	//_core.runLayerProject( _frontConfig );
	_core.runLayerProject( _themesConfig );
	_core.runLayerProject( _pluginsConfig );

	grunt.initConfig({
	    concat : _gruntModules.concat,

		uglify : _gruntModules.uglify,

	 	less   : _gruntModules.less,

	  	watch  : _gruntModules.watch,

	  	usebanner : _gruntModules.usebanner
	});

	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-less' );
	grunt.loadNpmTasks( 'grunt-banner' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );

	grunt.registerTask( 'run-less', [ 'less', 'usebanner' ] );
	grunt.registerTask( 'run-script', [ 'concat', 'uglify' ] );
}
