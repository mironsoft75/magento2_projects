var generateUrlKey = function(title){
    return title.split(' ').join('-').replace(/\./g,'-').replace(/[«»""!?',!@£$%^&*{};:()]+/g, '').toLowerCase().replace(/([-]{2,})/g,'-');
};