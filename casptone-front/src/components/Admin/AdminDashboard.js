import React from "react";
import { motion } from "framer-motion";

const AdminDashboard = () => {



    return (
        <div className="container mt-4">
                    <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ duration: 0.5 }}>
                    <div className="text-center mb-4">
                    <h2 style={{ color: 'black' }} >UNICK FURNITURE DASHBOARD</h2>
                    </div>
        </motion.div>
        </div>
    );
};

export default AdminDashboard;