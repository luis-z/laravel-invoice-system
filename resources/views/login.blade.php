@extends('layout.layout')

@section('content')
<template>
	<v-app>
	  <v-container fluid>
			<v-row>
				<v-card
					class="mx-auto"
					outlined
				>
					<form>
						<v-text-field
							v-model="email"
							label="Correo Electronico"
							required
						></v-text-field>

						<v-text-field
							v-model="password"
              				label="Clave"
							:append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
							:type="show1 ? 'text' : 'password'"
							@click:append="show1 = !show1"
						></v-text-field>
						<v-card-actions>
							<v-btn color="blue darken-1" text @click="login()">Login</v-btn>
						</v-card-actions>
					</form>

				</v-card>
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
				config: {
					headers: {
						Accept: 'application/json'
					}
				},
				email:'',
				password:'',
				show1: false,
  		}
		},
		created () {
			//
		},

		methods: {
			login () {
				
				var request = {
					email: this.email,
					password: this.password
				}

				axios.post('/api/login', request, this.config)
					.then(response => {
						

						if (response.data.token) {
							storeKey( response.data.token )
							Swal.fire('Login exitoso')
							window.location.replace('/api/inicio')
						} else {
							Swal.fire('Login fallido')
						}

					})
					.catch((error) => {
						
						Swal.fire({
							icon: 'error',
							title: 'Datos incorrectos',
							text: '',
						})
					})
			},

		},
  });

</script>
<style>
.v-card{
	margin-top:10rem!important;
	padding:2rem!important;
}
</style>
@endsection
