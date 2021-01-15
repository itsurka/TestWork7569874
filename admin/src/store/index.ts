import {createStore} from 'vuex'
import {Post, postFactory} from "@/components/blog/model/Post";
import {WebUser} from "@/components/blog/model/WebUser";
import {Common} from "@/components/blog/model/Common";
import axios from "axios";

interface RootState {
    posts: Post[],
    user: WebUser,
    common: Common
}

const userDefault = {
    _logged: false,
    id: '',
    email: '',
    token: ''
};

const commonDefault = {
    _loaded: false,
    brands: [],
    brand_models: [],
    gearbox_types: [],
    body_types: [],
    fuel_types: [],
    wheel_drive_types: [],
    cities: [],
}

// Store is initialised here - in the real world you would make an API call.
export default createStore<RootState>({
    state: {
        posts: [
            // populate with 5 dummy posts
            ...Array.from({length: 5}).map(() => postFactory())
        ],
        user: userDefault,
        common: commonDefault
    },
    getters: {
        allPosts(state) :Post[]{
            return state.posts
        },

        // Get all posts, ordered by upvotes:
        topPosts(state) :Post[]{
            return [...state.posts].sort((a, b) => {
                if (a.upvotes < b.upvotes) return 1;
                if (a.upvotes > b.upvotes) return -1;
                return 0;
            })
        },
        user(state) { return state.user; },
        isLogged(state) { return state.user._logged; },
        getCommon(state) { return state.common; },
        brands(state) { return state.common.brands; },
        brandModels(state) { return state.common.brand_models; },
        gearboxTypes(state) { return state.common.gearbox_types; },
        bodyTypes(state) { return state.common.body_types; },
        fuelTypes(state) { return state.common.fuel_types; },
        wheelDriveTypes(state) { return state.common.wheel_drive_types; },
        cities(state) { return state.common.cities; },
    },
    mutations: {
        deletePost(state, slug:string) :void{
            const index = state.posts.findIndex(post => post.slug === slug);
            if (index === -1) return;

            state.posts.splice(index, 1)
        },
        setWebUser(state, data) {
            if (!data.id) {
                return;
            }
            state.user._logged = true;
            state.user.id = data.id;
            state.user.email = data.email;
            state.user.token = data.token;

            axios.interceptors.request.use(function (config) {
                config.headers.Authorization =  state.user.token;
                return config;
            });

            window.localStorage.setItem('user', JSON.stringify(state.user));
        },
        setCommon(state, data) {
            let loaded = Object.keys(data.brands).length > 0 && Object.keys(data.brand_models).length > 0;
            state.common.brands = data.brands;
            state.common.brand_models = data.brand_models;
            state.common.gearbox_types = data.gearbox_types;
            state.common.body_types = data.body_types;
            state.common.fuel_types = data.fuel_types;
            state.common.wheel_drive_types = data.wheel_drive_types;
            state.common.cities = data.cities;
            state.common._loaded = loaded;
            if (loaded) {
                window.localStorage.setItem('common', JSON.stringify(state.common));
            }
        },
        logout(state) {
            state.user._logged = false;
            state.user.id = '';
            state.user.email = '';
            state.user.token = '';
            window.localStorage.removeItem('user');
        }
    },
    actions: {
        initStore(context) {
            let userData = window.localStorage.getItem('user');
            if (userData) {
                context.dispatch('setWebUser', JSON.parse(userData));
            }

            let commonData = window.localStorage.getItem('common');
            if (!commonData) {
                axios.get('/api/site/common').then(response => {
                    context.dispatch('action', {name: 'setCommon', data: response.data});
                });
            } else {
                context.dispatch('action', {name: 'setCommon', data: JSON.parse(commonData)});
            }
        },

        getPost(context, slug :string): Post | undefined {
            return context.state.posts.find(post => post.slug === slug)
        },

        deletePost(context, slug: string): void{
            context.commit('deletePost', slug);
        },

        setWebUser(context, user: object): void {
            context.commit('setWebUser', user);
        },
        action(context, params) {
            context.commit(params.name, params.data);
        },

        // Increment upvote count for a post:
        upvote(context, slug: string) :void{
            const post = context.state.posts.find(post => post.slug === slug)
            if (!post) return;
            post.upvotes++
        },

        // Create a new post:
        addPost(context, newPost: Post):void {
            context.state.posts.push({...newPost});
        },
        logout(context) {
            context.commit('logout');
        }
    },

    modules: {}
})
