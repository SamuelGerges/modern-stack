import express from 'express';
import verifySignature from '../middleware/verifySignature.js';
import validateBody from '../middleware/validateBody.js';
import { notify, listNotifications } from '../controllers/notifyController.js';


const router = express.Router();


router.post('/notify', verifySignature, validateBody, notify);
router.get('/notifications', listNotifications);


export default router;