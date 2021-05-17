<template>

<div>
<div class="content">

			<div class="container">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags<Button  @click='addModal=true' v-if="isWritePermitted" ><Icon type="md-add" />Add new tags</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag Name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(tag,i) in tags" :key="i"  >
								<td v-if="tags.length">{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
								      <Button type="info" size="small" @click="showEditModal(tag,i)" v-if="isUpdatePermitted" >Edit</Button>
									  <Button type="error" size="small" @click="showDeletingModal(tag,i)" :disabled="isDeleting" :loading="tag.isDeleting" v-if="isDeletePermitted" >{{ isDeleting ? 'Deleting...':'Delete'}}</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				<!-- <Page :total="100" /> --> <!-- Pagination -->
				</div>


				<!-- tag addition modal -->
                       <Modal
                            v-model="addModal"
                            title="Add Tag"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">
                              <Input v-model="data.tagName" placeholder="Add tag name" />
							 <div slot="footer">
							   <Button type="default" @click='addModal=false'>Close</Button>
							   <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding" v-if="isWritePermitted">{{ isAdding ? 'Adding...':'Add tag'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

		<!-- tag  edit modal -->
                       <Modal
                            v-model="editModal"
                            title="Edit Tag"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">
                              <Input v-model="editdata.tagName" placeholder="Edit tag name" />
							 <div slot="footer">
							   <Button type="default" @click='editModal=false'>Close</Button>
							   <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{ isAdding ? 'Editing..':'Edit tag'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

	<!-- tag deleting modal -->

	<!-- <Modal v-model="showDeleteModal" width="360" >
        <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
        </p>
        <div style="text-align:center">
           <p> Are you sure you want to delete this tag?</p>
        </div>
        <div slot="footer">
            <Button type="error" size="large" @click="deleteTag" long :loading="isDeleting" :disabled="isDeleting" >Delete</Button>
        </div>
    </Modal>
	-->
	<deletemodal> </deletemodal>

	<!-- tag deleting modal -->

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
			tagName:''
		},
		addModal: false,
		editModal: false,
		isAdding : false,
		tags:[],
		editdata:{
			tagName:''
		},
		index: -1,
		deleteItem: {},
		showDeleteModal :false,
		isDeleting : false,
		deletingIndex:-1
		
		
	}
},

methods: {
	async addTag(){
		if(this.data.tagName.trim()=='') return this.e('Tag Name is Required')
		const res = await this.callApi('post','/app/create_tag',this.data)
		if (res.status===201) {
			this.tags.unshift(res.data)
			this.s('Tag has been added ')
			this.addModal = false
			this.data.tagName=''
		}
		else{
			if (res.status===422) {
				if (res.data.errors.tagName) {
					this.e(res.data.errors.tagName[0])
				}
			}
			else{
			this.swr()

			}
		}
	},

	async editTag(){
		if(this.editdata.tagName.trim()=='') return this.e('Tag Name is Required')
		const res = await this.callApi('post','/app/edit_tag',this.editdata)
		if (res.status===200) {
			this.tags[this.index].tagName= this.editdata.tagName
			this.s('Tag has been edited ')
			this.editModal = false
		}
		else{
			if (res.status===422) {
				if (res.data.errors.tagName) {
					this.e(res.data.errors.tagName[0])
				}
			}
			else{
			this.swr()

			}
		}
	},
	showEditModal(tag,index){

		let obj = {
			id : tag.id,
			tagName : tag.tagName
		}
		this.editdata= obj
		this.editModal = true
		this.index = index

	},
	

	async deleteTag(){
		//this.set(tag,'isDeleting',true)
         this.isDeleting = true
		const res = await this.callApi('post','app/delete_tag',this.deleteItem)
		if (res.status===200) {
			this.tags.splice(this.deletingIndex,1)
			this.s('Tag has been deleted')
		}
		else{
			this.swr()
		}
		this.isDeleting = false
		this.showDeleteModal=false
		
	},
	showDeletingModal(tag,i){
			const  deletemodalobj  = {
                showdeletemodal:true,
                deleteUrl: 'app/delete_tag',
                data :tag,
                deletingIndex:i,
				isDeleted : false
				}
				this.$store.commit('setDeletingModalObj',deletemodalobj)
				console.log('delete method called')
		

	},
	
},
async created () {
	console.log(this.isWritePermitted)
	const res = await this.callApi('get','app/get_tags')
	if (res.status===200) {
		this.tags = res.data
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
			this.tags.splice(obj.deletingIndex,1)

				
			}
		}
}

}
</script>

<style>
 
</style>