<template>

<div>
<div class="content">

			<div class="container">
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0"> Assign role
                       <Select v-model="data.id" style="width:300px"  placeholder="Select Admin Type" @on-change="changerole"> 
                                    <Option   :value="r.id" v-for="(r,i) in roles " :key="i" ><span v-if="roles.length" ></span>{{r.roleName}}</Option>
                                    </Select>
                    </p>


					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
                                <th>Resource Name</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
								<th>Delete</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(r,i) in resources" :key="i">
								<td>{{r.resourcename}}</td>
								<td ><Checkbox v-model="r.read" ></Checkbox></td>
								<td ><Checkbox v-model="r.write" ></Checkbox></td>
								<td ><Checkbox v-model="r.update"></Checkbox></td>
								<td ><Checkbox v-model="r.delete"></Checkbox></td>

                            </tr>
								
								<!-- ITEMS -->
								<div class="center_button">
									<Button type="primary" :loading="isSending" :disabled="isSending" @click="assignroles" >Assign</Button>
								</div>

						</table>
					</div>
				<!-- <Page :total="100" /> --> <!-- Pagination -->
				</div>



	
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
		data:{
			roleName:'',
            id:null
			},
			isSending : false ,

            roles:[],

	
		resources:[
                {resourcename:'Home',read:false,write:false,update:false,delete:false, name : '/'},
			    {resourcename:'Tags',read:false,write:false,update:false,delete:false, name : 'tags'},
                {resourcename:'Category',read:false,write:false,update:false,delete:false, name : 'category'},
                {resourcename:'Create Blogs ',read:false,write:false,update:false,delete:false, name : 'createBlog'},
                {resourcename:' Blogs ',read:false,write:false,update:false,delete:false, name : 'blogs'},
				{resourcename:'Admin Users',read:false,write:false,update:false,delete:false, name : 'adminusers'},
                {resourcename:'Role',read:false,write:false,update:false,delete:false, name : 'role'},
                {resourcename:'Assign role',read:false,write:false,update:false,delete:false, name : 'assignrole'},

		],
			Iniitial_resources:[
                {resourcename:'Home',read:false,write:false,update:false,delete:false, name : '/'},
				{resourcename:'Tags',read:false,write:false,update:false,delete:false, name : 'tags'},
                {resourcename:'Category',read:false,write:false,update:false,delete:false, name : 'category'},
                {resourcename:'Create Blogs ',read:false,write:false,update:false,delete:false, name : 'createBlog'},
                {resourcename:' Blogs ',read:false,write:false,update:false,delete:false, name : 'blogs'},
				{resourcename:'Admin Users',read:false,write:false,update:false,delete:false, name : 'adminusers'},
                {resourcename:'Role',read:false,write:false,update:false,delete:false, name : 'role'},
                {resourcename:'Assign role',read:false,write:false,update:false,delete:false, name : 'assignrole'},

        ],
		
		
		
		
	}
},

methods: {

async assignroles() {

let data = JSON.stringify(this.resources)
const res = await this.callApi('post','app/assign_roles',{'permission' : data, id: this.data.id,})
if (res.status==200) {
	this.s('Role has been assigned successfully')
	  let index = this.roles.findIndex(role => role.id == this.data.id)
	  this.roles[index].permission = data
	
}
else{
	this.swr()
}

},
changerole(){
	  //console.log(this.data.id)
	  let index = this.roles.findIndex(role => role.id == this.data.id)
	  let permission = this.roles[index].permission

        if(!permission){
			this.resources = this.Iniitial_resources
		}
		else{
			this.resources = JSON.parse(permission)


		}

   }

	
	
},
async created () {
    //console.log(this.$route)
	const res = await this.callApi('get','app/get_roles')
	if (res.status===200) {
		this.roles = res.data
           if (res.data.length) {
			  // console.log(this.data.id)
		this.data.id = res.data[0].id
		if (res.data[0].permission) {
			//this.resources = JSON.parse(res.data[0].permission)
			this.resources = this.Iniitial_resources

		}
			   
		   }
	}
	else{
			this.swr()
		}
   },

 

   
}
</script>

<style>
 
</style>