const notifications = [];


export default {
    add(note) 
    { 
        notifications.push(note); 
        return note; 
    },
    getAll()
    {
        return notifications;
    },

    clear() 
    { 
        notifications.length = 0;
    }
};