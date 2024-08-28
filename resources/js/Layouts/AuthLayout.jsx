import React, { useEffect } from "react";
import { Container, Flex, VStack, useToast } from "@chakra-ui/react";

const AuthLayout = ({ children, sessions }) => {
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
        <VStack background={"gray.50"}>
            <Container>
                <Flex
                    justify="center"
                    w={"100%"}
                    alignItems="center"
                    minH={"100vh"}
                >
                    {children}
                </Flex>
            </Container>
        </VStack>
    );
};

export default AuthLayout;
