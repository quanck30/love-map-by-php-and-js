let currentShopsData = [];

export const setShopsData = (data) => {
  currentShopsData = [...data];
};

export const getShopsData = () => currentShopsData;
