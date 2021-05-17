import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)


export default new  Vuex.Store({
    state: {
        counter : 1000,
        deletemodalobj : {
                showdeletemodal:false,
                deleteUrl: '',
                data : null,
                deletingIndex:-1,
                isDeleted : false
            },
        user:false,
        userpermission:null,
    },
    getters: {
        getCounter(state){
            
           return state.counter  
        },
        getdeletemodalobj(state){
            return state.deletemodalobj
        },
        
        getUserPermission(state){
            return state.userpermission 
            
        }
       
    },
    mutations : {
        changeTheCounter(state,data){
        state.counter += data
            
        },
        setDeleteModal (state,data){
          const   deletemodalobj = {
                showdeletemodal:false,
                deleteUrl: '',
                data : null,
                deletingIndex:-1,
                isDeleted : data

        }
            state.deletemodalobj = deletemodalobj

        },
        setDeletingModalObj (state,data){
            state.deletemodalobj = data
        },
        setUpdateUser (state,data){
            state.user = data
        },
        setUserPermission(state,data){
            state.userpermission = data
        }
     },
        actions : {
            changeCounterAction({commit},data){
            commit('changeTheCounter',data)
            }
    }
})

