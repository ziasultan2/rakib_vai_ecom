import { login, logout, getInfo } from '@/api/auth';
import { isLogged, setLogged, removeToken } from '@/utils/auth';
import router, { resetRouter } from '@/router';
import store from '@/store';

const state = {
  carts: [],
  total: 0,
  due: 0,
};

const mutations = {
  ADD_TO_CART: (state, cart) => {
    state.carts.push(cart);
  },
};

const actions = {
  // add to cart
  addToCart({ commit, state }, cart) {
	commit('ADD_TO_CART', cart);
  },

  // user logout
  clearCart({ commit }) {
	commit('ADD_TO_CART', []);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
