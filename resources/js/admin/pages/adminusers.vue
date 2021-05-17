<template>

<div>
<div class="content">

			<div class="container">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Admin<Button  @click='addModal=true' v-if="isWritePermitted"><Icon type="md-add"  />Add new admin</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
								<th>User type</th>
								<th>Created At</th>

                                <th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(User,i) in Users" :key="i"  >
								<td v-if="Users.length">{{User.id}}</td>
								<td class="_table_name">{{User.fullname}}</td>
								<td class="">{{User.email}}</td>
								<td class="">{{User.role_id}}</td>
								<td class="">{{User.created_at}}</td>

								<td>
								      <Button type="info" size="small" @click="showEditModal(User,i)" v-if="isUpdatePermitted" >Edit</Button>
									  <Button type="error" size="small" @click="showDeletingModal(User,i)" :loading="User.isDeleting"  v-if="isDeletePermitted">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				<!-- <Page :total="100" /> --> <!-- Pagination -->
				</div>


				<!-- TBCname addition modal -->
                       <Modal
                            v-model="addModal"
                            title="Add Admin"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">
                             <div class="space">
                              <Input  type="text" v-model="data.fullname" placeholder="Full Name" />
							  </div>

                             <div class="space">
                              <Input  type="email" v-model="data.email" placeholder="Email" />
							  </div>

                             <div class="space">
                              <Input type="password" v-model="data.password" placeholder="Password" />
							  </div>

                             <div class="space">
                                   <Select v-model="data.role_id" style="width:200px" placeholder="Select Admin Type">
                                    <Option   :value="r.id" v-for="(r,i) in roles " :key="i" ><span v-if="roles.length" ></span>{{r.roleName}}</Option>
                                    </Select>
                             </div>

							 <div slot="footer">
							   <Button type="default" @click='addModal=false'>Close</Button>
							   <Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding" v-if="isWritePermitted" >{{ isAdding ? 'Adding..':'Add Admin'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

		<!-- Editadminusersname  edit modal -->
                       <Modal
                            v-model="editModal"
                            title="Edit Admin"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">
							  <div class="space">
                              <Input  type="text" v-model="editdata.fullname" placeholder="Full Name" />
							  </div>

                             <div class="space">
                              <Input  type="email" v-model="editdata.email" placeholder="Email" />
							  </div>

                             <div class="space">
                              <Input type="password" v-model="editdata.password" placeholder="Password" />
							  </div>

                             <div class="space">
                                   <Select v-model="editdata.role_id" style="width:200px" placeholder="Select Admin Type">
                                    <Option  :value="r.id" v-for="(r,i) in roles " :key="i" ><span v-if="roles.length"></span>{{r.roleName}}</Option>
                                   
                                  </Select>
                             </div>
							 <div slot="footer">
							   <Button type="default" @click='editModal=false'>Close</Button>
							   <Button type="primary" @click="editadmin" :disabled="isAdding" :loading="isAdding"  v-if="isWritePermitted" >{{ isAdding ? 'Editing..':'Edit Admin'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

	<!-- TBCname deleting modal -->

	<!-- <Modal v-model="showDeleteModal" width="360" >
        <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
        </p>
        <div style="text-align:center">
           <p> Are you sure you want to delete this TBCname?</p>
        </div>
        <div slot="footer">
            <Button type="error" size="large" @click="deleteTBCname" long :loading="isDeleting" :disabled="isDeleting" >Delete</Button>
        </div>
    </Modal>
	-->
	<deletemodal> </deletemodal>

	<!-- TBCname deleting modal -->

				</div>
         </div>
   </div>
</template>


<script>
import { mapGetters } from 'vuex';
import deletemodal from '../components/deletemodal.vue';

export default {
data(){
	return {
		data:{
            fullname:'',
            email:'',
            password:'',
			role_id:null
		},
		addModal: false,
		editModal: false,
		isAdding : false,
		Users:[],
		editdata:{
			 fullname:'',
            email:'',
			password:'',
			
			role_id:null,
		},
		index: -1,
		deleteItem: {},
		showDeleteModal :false,
		isDeleting : false,
		deletingIndex:-1,
		websiteSettings:[],
		roles:[]
		
		
	}
},

methods: {
	async addAdmin(){
        if(this.data.fullname.trim()=='') return this.e('Full Name Name is Required')
        if(this.data.email.trim()=='') return this.e('Email  is Required')
        if(this.data.password.trim()=='') return this.e('Password  is Required')
		if(!this.data.role_id) return this.e('Usertype  is Required')
        
        
        
		const res = await this.callApi('post','/app/create_user',this.data)
		if (res.status===201) {
			this.Users.unshift(res.data)
			this.s('Admin user has been added!')
			this.addModal = false
            this.data.fullname=''
            this.data.email=''
            this.data.passsword=''
            this.data.usertype=''

             
        
            
		}
		else{
			if (res.status===422) {
                for(let i in res.data.errors){
					this.e(res.data.errors[i] [0])

                }
			}
			else{
			this.swr()

			}
		}
	},

	async editadmin(){
		if(this.editdata.fullname.trim()=='') return this.e('Full Name Name is Required')
        if(this.editdata.email.trim()=='') return this.e('Email  is Required')
		if(!this.editdata.role_id) return this.e('Usertype  is Required')
		const res = await this.callApi('Post','/app/edit_users',this.editdata)

		if (res.status===200) {
			this.Users[this.index].fullname= this.editdata.fullname
			this.Users[this.index].email= this.editdata.email
			this.Users[this.index].password= this.editdata.password
			this.Users[this.index].role_id= this.editdata.role_id

            this.s('User has been edited ')
			this.editModal = false
		}
		else{
			if (res.status===422) {
				for(let i in res.data.errors){
					this.e(res.data.errors[i] [0])

                }
			}
			else{
				console.log(res.data.errors)
			this.swr()

			}
		}
	},
	showEditModal(User,index){

		let obj = {
			id : User.id,
			fullname : User.fullname,
			email : User.email,
             usertype : User.usertype

		}
		this.editdata= obj
		this.editModal = true
		this.index = index

	},
	

	async deleteadminusers(){
		//this.set(TBCname,'isDeleting',true)
         this.isDeleting = true
		const res = await this.callApi('post','app/delete_TBCname',this.deleteItem)
		if (res.status===200) {
			this.Users.splice(this.deletingIndex,1)
			this.s('TBCname has been deleted')
		}
		else{
			this.swr()
		}
		this.isDeleting = false
		this.showDeleteModal=false
		
	},
	showDeletingModal(TBCname,i){
			const  deletemodalobj  = {
                showdeletemodal:true,
                deleteUrl: 'app/delete_TBCname',
                data :TBCname,
                deletingIndex:i,
				isDeleted : false
				}
				this.$store.commit('setDeletingModalObj',deletemodalobj)
				console.log('delete method called')
		

	},
	
},
async created () {


	const [res, resRole] = await Promise.all([
 
	   this.callApi('get','app/get_users',this.data),
	   this.callApi('get','app/get_roles'),
	])
	   
	

	if (res.status===200) {
		this.Users= res.data
	}
	else{
			this.swr()
		}

		if (resRole.status===200) {
		this.roles= resRole.data
	}
	else{
			this.swr()
		}
},
components : {
	deletemodal
},
computed : {
     ...mapGetters(['getdeletemodalobj'])
},
watch : {
        getdeletemodalobj(obj){
			console.log(obj)
			if (obj.isDeleted) {
			this.Users.splice(obj.deletingIndex,1)

				
			}
		}
}

}
</script>

<style>
 
</style>