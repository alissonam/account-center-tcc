let random = function () {
  return Math.random().toString(36).substr(2);
};

export const randomToken = () =>{
  return random() + random();
};
