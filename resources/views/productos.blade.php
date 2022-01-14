@extends('layout.layout')

@section('content')
<template>
	<v-app>
	  <v-container fluid>
			<v-row v-if="!productoSeleccionado && !crear">
                
                <v-col cols="12" class="no-top">
					<v-data-table
						:headers="productosHeader"
						:items="productos"
						dark
						:items-per-page="10"
						:search="search"
						:sort-by="['id']"
						:sort-desc="[true]"
						class="elevation-1"
					>
						<template v-slot:item.action="{ item }">
							<v-icon small class="mr-2" @click="editarProducto(item)">
								Editar
							</v-icon>
							<v-icon small class="mr-2" @click="eliminarProducto(item)">
								Eliminar
							</v-icon>
						</template>

                        <template v-slot:top>
							<v-toolbar flat color="dark">
								<div id="custom-margin" style="margin-bottom: 1.5rem!important;">
									<v-text-field
										style="padding-left:1rem"
										v-model="search"
										label="Filtrar"
										single-line
										hide-details
										solo
										dense

									></v-text-field>
								</div>

                                <div style="margin-bottom: 1.5rem!important;">
                                    <v-btn color="blue darken-1" text @click="crear = true, productoSeleccionado = {}">
                                        Crear Producto
                                    </v-btn>
                                </div>
                                <div style="margin-bottom: 1.5rem!important;">
                                    <v-btn color="blue darken-1" text @click="listarFacturas">
                                        Listado de Facturas
                                    </v-btn>
                                </div>

                                <div style="margin-bottom: 1.5rem!important;">
                                    <v-btn color="red darken-1" text @click="logout()">Cerrar Sesion</v-btn>
                                </div>


								<v-divider
									class="mx-4"
									inset
									vertical
								></v-divider>
								<v-spacer></v-spacer>
							</v-toolbar>
						</template>
                </v-data-table>
            </v-row>
            <v-row v-else>
                <v-col cols="6">
                    <v-btn @click="productoSeleccionado = false, crear = false">
                        <h3>Regresar</h3>
                    </v-btn>
                </v-col>
                <v-col cols="6">
                    <h3 v-if="crear">
                        Crear Producto
                    </h3>
                    <h3 v-else>
                        Editar Producto
                    </h3>
                </v-col>
                <v-col cols="12">
                    <v-text-field
                        v-model="productoSeleccionado.nombre"
                        label="Nombre"
                    ></v-text-field>
                    <v-text-field
                        v-model="productoSeleccionado.precio"
                        label="Precio"
                        @keypress="isNumber($event)"
                    ></v-text-field>
                    <v-text-field
                        @keypress="isNumber($event)"
                        v-model="productoSeleccionado.impuesto"
                        label="Impuesto"
                    ></v-text-field>

                    <v-btn @click="submit">
                        <h3>Enviar</h3>
                    </v-btn>
                </v-col>
			</v-row>
		</v-container>
	</v-app>
</template>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script
src="https://code.jquery.com/jquery-3.4.1.min.js"
integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
crossorigin="anonymous"></script>

<script>

	new Vue({
		el: '#app',
		vuetify: new Vuetify(),
		data() {
			return {
				config: auth(), // public/js/functions/auth.js,
                admin: false,
                client: false,
                productos: [],
                search: '',
                productosHeader: [
                    {
                        text: 'ID',
                        align: 'center',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'center',
                        value: 'nombre'
                    },
                    {
                        text: 'Precio',
                        align: 'center',
                        value: 'precio'
                    },
                    {
                        text: 'Impuesto',
                        align: 'center',
                        value: 'impuesto'
                    },
                    {
                        text: 'Detalle',
                        align: 'center',
                        value: 'action'
                    }
                ],
                productoSeleccionado: false,
                crear: false
  		    }
		},
		created () {
			this.checkSession()
		},

		methods: {
			checkSession () {
				axios.post('/api/checksession', {}, this.config)
					.then(response => {
						

						if (response.status !== 200) {
                            window.location.replace('/api/inicio')
                        }
                        
                        switch (response.data.user_rol) {
                            case 1:
                                this.admin = true
                                this.client = false
                                this.listarProductos()
                                break;
                            case 2:
                                window.location.replace('/api/login')
                                break;
                        }
                        
					})
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Sesion vencida',
							text: '',
						})
					})
			},
            listarFacturas () {
                window.location.replace('/api/inicio')
            },
            editarProducto (producto) {
                this.productoSeleccionado = producto
            },
            submit () {

                let endpoint = ''
                if (this.crear) {
                    endpoint = '/api/crearproducto'
                } else {
                    endpoint = '/api/editarproducto/'+this.productoSeleccionado.id
                }
                axios.post(endpoint, this.productoSeleccionado, this.config)
					.then(response => {
                        this.listarProductos()
                        this.productoSeleccionado = false
                        this.crear = false
                    })
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Sesion vencida',
							text: '',
						})
                        // window.location.replace('/api/login')
					})
            },
            eliminarProducto (producto) {

                let endpoint = '/api/eliminarproducto/'+producto.id
                axios.post(endpoint, this.productoSeleccionado, this.config)
					.then(response => {
                        this.listarProductos()
                        this.productoSeleccionado = false
                    })
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Sesion vencida',
							text: '',
						})
                        // window.location.replace('/api/login')
					})
            },
            listarProductos () {
                
                axios.get('/api/listarproductos', {}, this.config)
					.then(response => {
						
                        console.log(`response.data.data`, response.data.data)
                        this.productos = response.data.data
                    })
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Sesion vencida',
							text: '',
						})
                        window.location.replace('/api/login')
					})
            },
            logout() {
                $('.loader').show()
                let request = {}

                axios.post('/api/logout', request, this.config)
                    .then(response => {
                        $('.loader').hide()
                        localStorage.setItem('ApiKey', '')
                        window.location.replace('/api/login')
                    })
                    .catch((error) => {
                        $('.loader').hide()
                        Swal.fire('Sesion Cerrada')
                        window.location.replace('/api/login')
                    })
            },
            isNumber: function(evt) {
                evt = evt ? evt : window.event
                var charCode = evt.which ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 9) {
                    evt.preventDefault()
                } else {
                    return true
                }
            },

		},
  });

</script>
<style>
.v-card{
	margin-top:2rem!important;
	padding:2rem!important;
}
</style>
@endsection
