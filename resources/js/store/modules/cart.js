import { login, logout, getInfo } from '@/api/auth';
import { isLogged, setLogged, removeToken } from '@/utils/auth';
import router, { resetRouter } from '@/router';
import store from '@/store';
import { start } from 'nprogress';
import { resolve } from 'core-js/fn/promise';

const state = {
  carts: [],
  cartQuantity: 0,
};

const mutations = {
  ADD_TO_CART: (state, cart) => {
    state.carts.push(cart);
    start.cartQuantity++;
  },
};

const actions = {
  // add to cart
  addToCart({ commit }, cart) {
    return new Promise(resolve => {
      commit('ADD_TO_CART', cart);
      resolve();
    });
  },

  // user logout
  clearCart({ commit }) {
    return new Promise(resolve => {
      commit('ADD_TO_CART', []);
      resolve();
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
