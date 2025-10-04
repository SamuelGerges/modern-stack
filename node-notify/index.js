import 'dotenv/config';
import app from './app.js';

const PORT = process.env.PORT || 3001;


// app.use(express.json({ type: '*/*' })); // keep raw JSON shape


app.listen(PORT, () => console.log(`node-notify on http://localhost:${PORT}`));