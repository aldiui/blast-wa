import React, { useEffect } from "react";
import { VStack, useToast } from "@chakra-ui/react";
import Footer from "../Components/Footer";
import Navbar from "../Components/Navbar/Index";

const AppLayout = ({ children, auth, sessions }) => {
    const toast = useToast();

    useEffect(() => {
        const displayMessage = (type, message) => {
            if (message) {
                toast({
                    title: type,
                    description: message,
                    status: type.toLowerCase(),
                    duration: 3000,
                    isClosable: true,
                    position: "top-right",
                    variant: "left-accent",
                });
            }
        };

        displayMessage("Success", sessions?.success);
        displayMessage("Error", sessions?.error);
    }, [sessions, toast]);

    return (
        <VStack>
            <Navbar auth={auth} sessions={sessions} />
                {children}
            <Footer />
        </VStack>
    );
};

export default AppLayout;
