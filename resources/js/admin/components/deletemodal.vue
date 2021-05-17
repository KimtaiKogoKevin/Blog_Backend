<template>
    <div>
        <Modal :value="getdeletemodalobj.showdeletemodal" width="360" >
        <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
        </p>
        <div style="text-align:center">
           <p> {{getdeletemodalobj.msg}} </p>
        </div>
        <div slot="footer">

            <Button type="error" size="large" @click="deletetag" long :loading="isDeleting" :disabled="isDeleting"   >Delete</Button>

       </div>
    </Modal>

    </div>
</template>

<script>
import {mapGetters} from 'vuex'
export default {
    data() { 
        return {
        isDeleting : false,
        }
    },

    methods:{
        async deletetag(){
            
         this.isDeleting = true
		const res = await this.callApi('post',this.getdeletemodalobj.deleteUrl,this.getdeletemodalobj.data)
		if (res.status===200) {
            this.s(this.getdeletemodalobj.successMsg)
           this.$store.commit('setDeleteModal',true)
         }
		else{
            this.swr()
           this.$store.commit('setDeleteModal',false)
            
        }
         this.isDeleting = false


        
     },
     closeModal(){
           this.$store.commit('setDeleteModal',false)


     }
    },
    computed : {
       ...mapGetters(['getdeletemodalobj'])
    }
    
}
</script>