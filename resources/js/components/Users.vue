<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Table</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal"> Add New <i
                                    class="fas fa-user-plus fa-fw"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>TYPE</th>
                                    <th>Register At</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.type | upText}}</td>
                                    <td>{{ user.created_at |myDate }}</td>
                                    <td>
                                        <a href="#"  @click="editModal(user)" > <i class="fa fa-edit blue"></i></a> / <a href="#" @click="deleteUser(user.id)"> <i
                                                class="fa fa-trash red"></i></a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" v-show="!editMode"> Add new User</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-show="editMode"> Edit user's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateUser() : CreateUser()">
                        <div class="modal-body">

                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email" type="text" name="email" placeholder="Email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.bio" name="bio" placeholder="Short bio for the user"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                <has-error :form="form" field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <select v-model="form.type" type="type" name="type" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">Standard User</option>
                                    <option value="admin">Author</option>
                                </select>
                                <has-error :form="form" field="type"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" placeholder="Password"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" v-show="!editMode">Create</button>
                            <button type="submit" class="btn btn-success" v-show="editMode">Update</button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
import { setInterval } from 'timers';
    export default {
        data() {
            return {
                editMode:false,
                users:{},
                form: new Form({
                    id:'',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    phpto: ''
                })
            }
        },
        methods: {
            updateUser(id){
                //progresse bar debut
                this.$Progress.start();
               // console.log("editing mode")
               this.form.put('api/user/'+this.form.id)
               .then(() => {
                   swal.fire(
                                'Udated!',
                                'Information has ben updaded.',
                                'success'
                            )
                $('#addNew').modal('hide')

                Fire.$emit('afterCreate');

                 this.$Progress.finish();
               }).catch(()=>{
                    this.$Progress.fail();
               });
            },
             editModal(user){
                this.form.reset()
                this.form.clear()
                this.editMode=true;
                $('#addNew').modal('show')
                this.form.fill(user);
            },
            newModal(){

                this.form.reset()
                 this.form.clear()
                 this.editMode=false;
                $('#addNew').modal('show')
            },
            deleteUser(id) {


                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        //send request to the sever
                        this.form.delete('api/user/' + id).then(() => {

                            swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )

                            Fire.$emit('afterCreate');

                        }).catch(() => {
                            swal("Failed!", "There was something wronge.", "warning")
                        });
                    }
                })
            },
            loadUsers(){
                axios.get("api/user").then(({ data }) => { this.users = data.data })
            },
            CreateUser() {

                //progresse bar debut
                this.$Progress.start();
                // requete ajax
                this.form.post('api/user').then(() => {
                        //creation d'un evenement apres la creation d'un user
                        Fire.$emit('afterCreate');
                        //on hide le modal
                        $('#addNew').modal('hide')

                        // message toast
                        Toast.fire({
                            type: 'success',
                            title: 'User created in successfully'
                            })

                        // finish progrsse bar
                        this.$Progress.finish();

                }).catch(() => {
                    swal("Failed!", "There was something wronge.", "warning")
                });


            }
        },
        created() {
            this.loadUsers()
            //sent get request after 3s
            //setInterval(()=>this.loadUsers(),3000);

            //on ecoute l'evenement apres la creation d'un user
            Fire.$on('afterCreate',() =>{
                this.loadUsers()
            });
        }
    }
</script>
