import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
//import firstpage from './components/pages/myfirstvuepage'
//import newRoutePage from './components/pages/newroutepage'
//import hooks from './components/pages/basic/hooks'
//import methods from './components/pages/basic/methods'

//project pages
import home from './admin/pages/home'

import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'
import createBlog from './admin/pages/createBlog'
import blogs from './admin/pages/blogs'
import editblog from './admin/pages/editblog'
import notfound from './admin/pages/notfound'














const routes = 
[

    //project routes 
    {
        path:'/login',
        component : login,
        name:'login'
    },
      {
          path: '/',
          component: home,
          name:'home',
      } ,

      {
          path:'/tags',
          component: tags ,
          name:'tags' 

      },

      {
          path:'/category',
          component : category,
          name:'category'
      },
      {
        path:'/createBlog',
        component : createBlog,
        name:'createBlog'
    },
   
    {
        path:'/adminusers',
        component : adminusers,
        name:'adminusers'
    },
    {
        path:'/role',
        component : role,
        name:'role',
    },
    {
        path:'/assignRole',
        component : assignRole,
        name:'assignRole'
    },
    {
        path:'/blogs',
        component : blogs,
        name:'blogs'
    },
    {
        path:'/editblog/:id',
        component : editblog,
        name:'editblog'
    },
    {
        path:'*',
        component : notfound,
        name:'notfound'
    },


    ]
    export default new Router ({

        mode:'history',
        routes
    
    }) 




/** 

    {
        path:'/my-new-vue-route',
        component: firstpage
    },
    {
        path : '/new-route',
        component: newRoutePage
    },
        //vue hooks

    {

        path : '/hooks',
        component: hooks
        
    },

    //basics
    {
        path: '/methods',
        component: methods
    }
]



*/