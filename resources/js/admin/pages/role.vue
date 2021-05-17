<template>
 <div>
   <div class="content">
      <div class="container-fluid">
			<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management<Button  @click='addModal=true' v-if="isWritePermitted"><Icon type="md-add" />Add  a new role</Button></p>
                     <div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>role type</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(role,i) in roles" :key="i"  >
								<td  v-if="roles.length">{{role.id}}</td>
								<td class="_table_name">{{role.roleName}}</td>
								<td>{{role.created_at}}</td>
								<td>
								      <Button type="info" size="small" @click="showEditModal(role,i)" v-if="isUpdatePermitted">Edit</Button>
									  <Button type="error" size="small" @click="showDeletingModal(role,i)" :disabled="isDeleting" :loading="role.isDeleting" v-if="isDeletePermitted">{{ isDeleting ? 'Deleting...':'Delete'}}</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				</div>


				<!-- role addition modal -->
                       <Modal
                            v-model="addModal"
                            title="Add role"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">
                              <Input v-model="data.roleName" placeholder="Add role name" />
							 <div slot="footer">
							   <Button type="default" @click='addModal=false'>Close</Button>
							   <Button type="primary" @click="addrole" :disabled="isAdding" :loading="isAdding" >{{ isAdding ? 'Adding...':'Add role'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

		<!-- role  edit modal -->
                       <Modal
                            v-model="editModal"
                            title="Edit role"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">
                              <Input v-model="editdata.roleName" placeholder="Edit role name" />
							 <div slot="footer">
							   <Button type="default" @click='editModal=false'>Close</Button>
							   <Button type="primary" @click="editrole" :disabled="isAdding" :loading="isAdding"  >{{ isAdding ? 'Editing..':'Edit role'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

	<!-- role deleting modal -->

	<!-- <Modal v-model="showDeleteModal" width="360" >
        <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
        </p>
        <div style="text-align:center">
           <p> Are you sure you want to delete this role?</p>
        </div>
        <div slot="footer">
            <Button type="error" size="large" @click="deleterole" long :loading="isDeleting" :disabled="isDeleting" >Delete</Button>
        </div>
    </Modal>
	-->

	<!-- role deleting modal -->
	<deleteModal></deleteModal>

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
			roleName:''
		},
		addModal: false,
		editModal: false,
		isAdding : false,
		roles:[],
		editdata:{
			roleName:''
		},
		index: -1,
		deleteItem: {},
		showDeleteModal :false,
		isDeleting : false,
		deletingIndex:-1
		
		
	}
},

methods: {
	async addrole(){
		if(this.data.roleName.trim()=='') return this.e('role Name is Required')
		const res = await this.callApi('post','/app/create_roles',this.data)
		if (res.status===201) {
			this.roles.unshift(res.data)
			this.s('role has been added ')
			this.addModal = false
			this.data.roleName=''
		}
		else{
			if (res.status===422) {
				if (res.data.errors.roleName) {
					this.e(res.data.errors.roleName[0])
				}
			}
			else{
			this.swr()

			}
		}
	},

	async editrole(){
		if(this.editdata.roleName.trim()=='') return this.e('role Name is Required')
		const res = await this.callApi('post','/app/edit_roles',this.editdata)
		if (res.status===200) {
			this.roles[this.index].roleName= this.editdata.roleName
			this.s('role has been edited ')
			this.editModal = false
		}
		else{
			if (res.status===422) {
				if (res.data.errors.roleName) {
					this.e(res.data.errors.roleName[0])
				}
			}
			else{
			this.swr()

			}
		}
	},
	showEditModal(role,index){

		let obj = {
			id : role.id,
			roleName : role.roleName
		}
		this.editdata= obj
		this.editModal = true
		this.index = index

	},
	

	async deleterole(){
		//this.set(role,'isDeleting',true)
         this.isDeleting = true
		const res = await this.callApi('post','app/delete_role',this.deleteItem)
		if (res.status===200) {
			this.roles.splice(this.deletingIndex,1)
			this.s('role has been deleted')
		}
		else{
			this.swr()
		}
		this.isDeleting = false
		this.showDeleteModal=false
		
	},
	showDeletingModal(role,i){
			const  deletemodalobj  = {
                showdeletemodal:true,
                deleteUrl: 'app/delete_role',
                data :role,
                deletingIndex:i,
				isDeleted : false
				}
				this.$store.commit('setDeletingModalObj',deletemodalobj)
				console.log('delete method called')
		

	},
	
},
async created () {
    //console.log(this.$route)
                        console.log('not equal');
        
	const res = await this.callApi('get','app/get_roles')
	if (res.status===200) {
		this.roles = res.data
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
			this.roles.splice(obj.deletingIndex,1)

				
			}
		}
}

}
</script>

<style>
 
</style>