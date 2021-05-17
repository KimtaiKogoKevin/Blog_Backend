<template>
<!-- eslint-disable vue/no-use-v-if-with-v-for,vue/no-confusing-v-for-v-if -->
<div>
<div class="content">

			<div class="container">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Category<Button  @click='addModal=true' v-if="isWritePermitted"><Icon type="md-add"  />Add new Category</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Category Image</th>
								<th>Category Name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(category,i) in categories" :key="i" v-if="categories.length">
								<td>{{category.id}}</td>
								<td class="table_image">
								<img :src="category.IconImage" />
								</td>

								<td class="_table_name">{{category.categoryName}}</td>
								<td>{{category.created_at}}</td>
								<td>
								      <Button type="info" size="small" @click="showEditModal(category,i)" v-if="isUpdatePermitted" >Edit</Button>
									  <Button type="error" size="small" @click="showDeletingModal(category,i)" :loading="category.isDeleting" v-if="isDeletePermitted" >Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				<!-- <Page :total="100" /> --> <!-- Pagination -->
				</div>


				<!-- Category addition modal -->
                       <Modal
                            v-model="addModal"
                            title="Add Category"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">

                <Input v-model="data.categoryName" placeholder="Add category name" />

                    <div class="space"> </div>


                    <Upload
					   ref="uploads"
					    type="drag"
                       :headers="{'x-csrf-token' : token,'X-Requested-With': 'XMLHttpRequest' }"
					   :on-success="handleSuccess"
					   :format="['jpg','jpeg','png']"
					   :on-error="handleError"
					   :on-format-error="handleFormatError"
                       :max-size="2048"
                       :on-exceeded-size="handleMaxSize"
                       action="/app/upload">
                      <div style="padding: 20px 0">
                      <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                       <p>Click or drag files here to upload</p>
                      </div>
                 </Upload>
                            
				    <div class="demo-upload-list" v-if="data.IconImage"> 
                        
						 <img :src="`${data.IconImage}`">
                                
						  <div class="demo-upload-list-cover">
							   <Icon type="ios-trash-outline" @click='deleteImage'></Icon>
                         </div>
						  </div>
				
				 <div slot="footer">
							   <Button type="default" @click='addModal=false'>Close</Button>
							   <Button type="primary" @click="addcategory" :disabled="isAdding" :loading="isAdding" v-if="isWritePermitted"  >{{ isAdding ? 'Adding..':'Add category'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

		<!-- category  edit modal -->
                       <Modal
                            v-model="editModal"
                            title="Edit category"
                            :mask-closable="false"
                            :closable="false"
                             @on-ok="ok"
                             @on-cancel="cancel">
                             <Input v-model="editdata.categoryName" placeholder="Edit category name" />

                    <div class="space"> </div>


                    <Upload v-show="isIconImagenew"
					   ref="editdatauploads"
                       type="drag"
                       :headers="{'x-csrf-token' : token,'X-Requested-With': 'XMLHttpRequest' }"
					   :on-success="handleSuccess"
					   :format="['jpg','jpeg','png']"
					   :on-error="handleError"
					   :on-format-error="handleFormatError"
                       :max-size="2048"
                       :on-exceeded-size="handleMaxSize"
                       action="/app/upload">
                      <div style="padding: 20px 0">
                      <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                       <p>Click or drag files here to upload</p>
                      </div>
                 </Upload>

				    <div class="demo-upload-list" v-if="editdata.IconImage">
                         <img :src="`${editdata.IconImage}`">

						  <div class="demo-upload-list-cover">
                      <Icon type="ios-trash-outline" @click='deleteImage(false)'></Icon>
                         </div>
						  </div>
							 <div slot="footer">
							   <Button type="default" @click='closeEditModal'>Close</Button>
							   <Button type="primary" @click="editcategory" :disabled="isAdding" :loading="isAdding" v-if="isUpdatePermitted" >{{ isAdding ? 'Editing..':'Edit Category'}}</Button>
                             </div>

                        </Modal>

	<!--end of modal -->

	
	<deletemodal> </deletemodal>

	<!-- category deleting modal -->

				</div>
         </div>
   </div>
</template>


<script>
import deletemodal from '../components/deletemodal.vue';

import { mapGetters } from 'vuex';

export default {
data(){
	return {
		data:{
			categoryName:'',
			IconImage:'',
		},
		addModal: false,
		editModal: false,
		isAdding : false,
		categories:[],
		editdata:{
			categoryName:'',
			IconImage:'',
			},
		index: -1,
		deleteItem: {},
		showDeleteModal :false,
		isDeleting : false,
		deletingIndex:-1,
		isIconImagenew: false,
		isEditingItem:false,
		
		
	}
},

methods: {
	async addcategory(){
		//console.log(this.data.IconImage)

		if(this.data.categoryName.trim()=='') return this.e('Category Name is Required')
		if(this.data.IconImage.trim()=='') return this.e('Category Image is Required')
		this.data.IconImage=`${this.data.IconImage}`
		const res = await this.callApi('post','app/create_category',this.data)
		if (res.status===201) {
			this.categories.unshift(res.data)
			this.s('Category has been added ')
			this.addModal = false
			this.data.categoryName=''
			this.data.IconImage=''

		}
		else{
			if (res.status===422) {
				if (res.data.errors.categoryName) {
					this.e(res.data.errors.categoryName[0])
				}
				if (res.data.errors.IconImage) {
					this.e(res.data.errors.IconImage[0])
				}
			}
			else{
			this.swr()

			}
		}
	},

	async editcategory(){
		if(this.editdata.categoryName.trim()=='') return this.e('Category Name is Required')
		if(this.editdata.IconImage.trim()=='') return this.e('Category Image is Required')
		const res = await this.callApi('post','/app/edit_category',this.editdata)
		if (res.status===200) {
			this.categories[this.index].categoryName= this.editdata.categoryName
			this.s('Category has been edited ')
			this.editModal = false
		}
		else{
			if (res.status===422) {
				if (res.data.errors.categoryName) {
					this.e(res.data.errors.categoryName[0])
				}
				if (res.data.errors.IconImage) {
					this.e(res.data.errors.IconImage[0])
				}
			}
			else{
			this.swr()

			}
		}
	},
	showEditModal(category,index){

		//let obj = {
		//	id : category.id,
			//categoryName : category.categoryName
		//}
		this.editdata= category
		this.editModal = true
		this.index = index
		this.isEditingItem = true

	},
	

	showDeletingModal(category,i){
		const  deletemodalobj = {
                showdeletemodal:true,
                deleteUrl: 'app/delete_category',
                data : category,
                deletingIndex:i,
				isDeleted : false
				}
				this.$store.commit('setDeletingModalObj',deletemodalobj)

		//this.deleteItem= category
		//this.deletingIndex = i
		//this.showDeleteModal=true

	},
	 handleSuccess (res, file) {
		  res = `/uploads/${res}`;
        this.data.IconImage = res

		 if (this.isEditingitem) {
			 return this.editdata.IconImage=res
		 }
               this.editdata.IconImage = res
			},
	 handleError (res, file) {
		 console.log('res',res)
		 console.log('file',file)

		  this.$Notice.warning({
                    title: 'The file Format is Incorrect',
                    desc: `${file.errors.file.length ? file.errors.file[0] : 'Something Went Wrong'}`
                });

            },
            handleFormatError (file) {
                this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },
            handleMaxSize (file) {
                this.$Notice.warning({
                    title: 'Exceeding file size limit',
                    desc: 'File  ' + file.name + ' is too large, no more than 2M.'
                });
			},
			async deleteImage(isAdd=true){
                   let image
                   if(!isAdd){//for editing
					   this.isIconImagenew=true
					     image = this.editdata.IconImage
				        this.editdata.IconImage = ''
				       this.$refs.editdatauploads.clearFiles()
				   }else{
					     image = this.editdata.IconImage
				        this.editdata.IconImage = ''
				       this.$refs.editdatauploads.clearFiles()
				   }
				 
				const res = await this.callApi('post','app/delete_image',{imageName : image})
				if (res.status!=200) {
					this.data.IconImage = image
					this.swr()
					
				}
				  
			},

			closeEditModal(){
				this.isEditingItem = false
				this.editModal = false
			}
	
},
async created () {

    this.token = window.Laravel.csrfToken
	const res = await this.callApi('get','app/get_category',)
	if (res.status===200) {
		this.categories= res.data

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
			this.categories.splice(obj.deletingIndex,1)

				
			}
		}
}

}
</script>

<style>
  .demo-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
 
</style>