import expressQueue from 'express-queue';

const queue = expressQueue({
    concurrent: 1,
    queueLimit: 0,
    timeout: 5000,
});

export default queue;
