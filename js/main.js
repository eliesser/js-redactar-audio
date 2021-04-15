window.addEventListener('DOMContentLoaded', function () {
 //window.alert('Flifli');
	
  var Vue = window.Vue;
  var URL = window.URL || window.webkitURL;
  var ImageCompressor = window.ImageCompressor;

  new Vue({
    el: '#app',

    data: function () {
      var vm = this;

      return {
        options: {
          checkOrientation: true,
          maxWidth: undefined,
          maxHeight: undefined,
          minWidth: 0,
          minHeight: 0,
          width: 1000,
          height: undefined,
          quality: 0.8,
          mimeType: '',
          convertSize: 5000000,
          success: function (file) {
            console.log('Salida: ', file);
			
			let name = document.getElementById('file').value;
			name = name.replace('C:\\fakepath\\','');

			// Subir al servidor con VUE.JS y AXIOS
			let formData = new FormData();
            formData.append('file', file, name);
			axios.post( 'http://localhost/app/modal/imagen-ajax.php',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(response){
                    //console.log('SUCCESS!!');
					console.log(response);
					vm.resultadoPHP = response.data;
                })
                    .catch(function(){
                        console.log('FAILURE!!');
            });
			//-----------------------------------------------


            if (URL) {
              vm.outputURL = URL.createObjectURL(file);
			  //vm.resultadoPHP = "Algo";
            }

            vm.output = file;
            //vm.$refs.input.value = '';
			//vm.$refs.input2.value = file;
          },
          error: function (e) {
            window.alert(e.message);
          },
        },
        inputURL: '',
        outputURL: '',
		resultadoPHP:'',
        input: {},
        output: {},
      };
    },

    filters: {
      prettySize: function (size) {
        var kilobyte = 1024;
        var megabyte = kilobyte * kilobyte;

        if (size > megabyte) {
          return (size / megabyte).toFixed(2) + ' MB';
        } else if (size > kilobyte) {
          return (size / kilobyte).toFixed(2) + ' KB';
        } else if (size >= 0) {
          return size + ' B';
        }

        return 'N/A';
      },
    },

    methods: {
      compress: function (file) {
        if (!file) {
          return;
        }

        console.log('Entrada: ', file);

		document.getElementById('MuestraThumbnail').innerHTML="<h2>Cargando...</h2>";

        if (URL) {
          this.inputURL = URL.createObjectURL(file);
        }

        this.input = file;
        new ImageCompressor(file, this.options);
      },

      change: function (e) {
        this.compress(e.target.files ? e.target.files[0] : null);
      },

      dragover: function(e) {
        e.preventDefault();
      },

      drop: function(e) {
        e.preventDefault();
        this.compress(e.dataTransfer.files ? e.dataTransfer.files[0] : null);
      },
    },
  });



});
