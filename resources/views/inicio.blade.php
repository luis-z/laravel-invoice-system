@extends('layout.layout')

@section('content')
<template>
	<v-app>
	  <v-container fluid>
			<v-row v-if="admin">
                
                <v-col cols="12" class="no-top">
					<v-data-table
                        v-if="!detalle"
						:headers="facturasHeader"
						:items="facturas"
						dark
						:items-per-page="10"
						:search="search"
						:sort-by="['id']"
						:sort-desc="[true]"
						class="elevation-1"
					>
						<template v-slot:item.action="{ item }">
							<v-icon small class="mr-2" @click="obtenerDetalleFactura(item)">
								Detalle
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
                                    <v-btn color="blue darken-1" text @click="facturar()">
                                        Facturar
                                    </v-btn>
                                </div>
                                <div style="margin-bottom: 1.5rem!important;">
                                    <v-btn color="blue darken-1" text @click="crudProductos()">
                                        CRUD Productos
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
                <v-data-table
                    v-else
                    :headers="detalleHeader"
                    :items="detalleFactura"
                    dark
                    :items-per-page="10"
                    :search="search"
                    :sort-by="['id']"
                    :sort-desc="[true]"
                    class="elevation-1"
                >
                    <template v-slot:top>
                        <v-toolbar flat color="dark">

                            <h4 style="margin-right: 2rem">
                                Desglose de Factura
                            </h4>
                            <div style="margin-top: 1.5rem!important; margin-right: 2rem">
                                <v-text-field
                                    v-model="facturaSeleccionada.cliente"
                                    label="Cliente"
                                ></v-text-field>
                            </div>
                            <div style="margin-top: 1.5rem!important; margin-right: 2rem">
                                <v-text-field
                                    v-model="facturaSeleccionada.costo_total"
                                    label="Monto Total"
                                ></v-text-field>
                            </div>
                            <div style="margin-top: 1.5rem!important; margin-right: 2rem">
                                <v-text-field
                                    v-model="facturaSeleccionada.impuesto_total"
                                    label="Impuesto Total"
                                ></v-text-field>
                            </div>
                            <div style="margin-top: 1.5rem!important; margin-right: 2rem">
                                <v-text-field
                                    v-model="facturaSeleccionada.cantidad_productos"
                                    label="Total de Productos"
                                ></v-text-field>
                            </div>
                            <div style="margin-bottom: 1.5rem!important;">
                                <v-btn color="blue darken-1" text @click="detalle = false">
                                    Regresar
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
            <v-row v-if="client">

                <v-col cols="12" sm="12" md="3">
                    <v-select
                        v-model="productoSeleccionado"
                        :items="productos"
                        item-text="nombre"
                        item-value="id"
                        label="Seleccionar producto*"
                        required
                    >
                    </v-select>
                    <v-btn color="blue darken-1" text @click="comprar()">Comprar</v-btn>
                    <v-btn color="red darken-1" text @click="logout()">Cerrar Sesion</v-btn>
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
                facturas: [],
                search: '',
                facturasHeader: [
                    {
                        text: 'ID',
                        align: 'center',
                        value: 'id'
                    },
                    {
                        text: 'Cliente',
                        align: 'center',
                        value: 'cliente'
                    },
                    {
                        text: 'Cantidad de Productos',
                        align: 'center',
                        value: 'cantidad_productos'
                    },
                    {
                        text: 'Costo Total',
                        align: 'center',
                        value: 'costo_total'
                    },
                    {
                        text: 'Impuesto Total',
                        align: 'center',
                        value: 'impuesto_total'
                    },
                    {
                        text: 'Detalle',
                        align: 'center',
                        value: 'action'
                    },
                ],
                detalleHeader: [
                    {
                        text: 'ID',
                        align: 'center',
                        value: 'id'
                    },
                    {
                        text: 'Nombre Producto',
                        align: 'center',
                        value: 'nombre_producto'
                    },
                    {
                        text: 'Precio de Producto',
                        align: 'center',
                        value: 'precio_producto'
                    },
                    {
                        text: 'Impuesto de Producto',
                        align: 'center',
                        value: 'impuesto_producto'
                    }
                ],
                detalleFactura: [],
                detalle: false,
                facturaSeleccionada: [],
                productos: [],
                productoSeleccionado: {}
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
                                this.listarFacturas()
                                break;
                            case 2:
                                this.admin = false
                                this.client = true
                                this.listarProductos()
                                break;
                        }
                        
					})
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: '',
						})
					})
			},
            facturar () {
                axios.post('/api/generarfactura', {}, this.config)
					.then(response => {
						
                        Swal.fire('Facturacion realizada')
                        this.listarFacturas()
                    })
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: '',
						})
					})
            },
            listarFacturas () {
                
                axios.post('/api/listarfacturas', {}, this.config)
					.then(response => {
						
                        console.log(`response.data.data`, response.data.data)
                        this.facturas = response.data.data
                    })
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: '',
						})
					})
            },
            obtenerDetalleFactura (factura) {
                this.facturaSeleccionada = factura
                axios.post('/api/detallefactura', {factura_id: factura.id}, this.config)
					.then(response => {
						
                        this.detalleFactura = response.data.data
                        this.detalle = true
                    })
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: '',
						})
					})
            },
            comprar () {
                axios.post('/api/agregarcompra', { producto_id: this.productoSeleccionado }, this.config)
					.then(response => {
						
                        console.log(`response.data.data`, response.data.data)
                        Swal.fire('Compra realizada exitosamente')
                    })
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: '',
						})
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
            crudProductos () {
                window.location.replace('/api/crudproductos')
            }

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
