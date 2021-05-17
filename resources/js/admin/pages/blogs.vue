<template>

<div>
<div class="content">

			<div class="container">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Add Blogs<Button  @click="$router.push('createBlog')" v-if="isWritePermitted" ><Icon type="md-add" />create Blogs</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Title</th>
                                <th>Category</th>
                                <th>Tags</th>
                                <th>Views</th>
                                <th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog,i) in blogs" :key="i"  v-if="blogs.length" >
								<td >{{blog.id}}</td>
								<td  class="_table_name">{{blog.title}}</td>
								<td > <span v-for="(c,i) in blog.cat" v-if="blog.cat.length"> <Tag type="border"> {{ c.categoryName }} </Tag> </span></td>
								<td > <span v-for="(t,i) in blog.tag" v-if="blog.tag.length"> <Tag type="border"> {{ t.tagName }}</Tag></span> </td>
								<td > {{ blog.veiws}}</td>
								<td>
								      <Button type="info" size="small"  >Visit Blog</Button>
                                      <Button type="info" size="small" @click="$router.push(`/editblog/${blog.id}`)" v-if="isUpdatePermitted" >Edit</Button>
									  <Button type="error" size="small" @click="showDeletingModal(blog,i)" :disabled="isDeleting" :loading="blog.isDeleting" v-if="isDeletePermitted" >{{ isDeleting ? 'Deleting...':'Delete'}}</Button>
								</td>
							</tr>

								<!-- ITEMS -->

						</table>
				 <Page :total="pageInfo.total" :current="pageInfo.current_page" :page-size="parseInt(pageInfo.per_page)"  @on-change="getBlogData"   v-if="pageInfo" />  <!-- Pagination -->

					</div>
				</div>


				<!-- tag addition modal -->
                       

	<!--end of modal -->

		<!-- tag  edit modal -->
                       

	<!--end of modal -->

	<!-- tag deleting modal -->
  <deletemodal> </deletemodal>


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
		
		isAdding : false,
		blogs:[],
		tags : [],
		index: -1,
		deleteItem: {},
		showDeleteModal :false,
		isDeleting : false,
		deletingIndex:-1,
		total: 1,
		pageInfo:null
		
		
	}
},

methods: {
	showDeletingModal(blogs,i){
        console.log('the index is ',i)
			const  deletemodalobj  = {
                showdeletemodal:true,
                deleteUrl: 'app/delete_blog',
                data :{id : blogs.id},
                deletingIndex:i,
				isDeleted : false,
                msg : 'Are you want to delete this Blog? ',
                successMsg: ' Blog has been deleted'
				}
				this.$store.commit('setDeletingModalObj',deletemodalobj)
				console.log('delete method called')
		

	},
	async getBlogData(page = 1){
	const res = await this.callApi('get',`app/blogsdata?page=${page}&total=${this.total}`)
	if (res.status==200) {
		this.blogs = res.data.data
		this.pageInfo = res.data 
	}
	else{
			this.swr()
		}
	},
},
async created () {
	this.getBlogData()
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
			this.blogs.splice(obj.deletingIndex,1)

				
			}
		}
}

}
</script>

<style>
 
</style>