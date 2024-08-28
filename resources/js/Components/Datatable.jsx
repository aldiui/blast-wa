import React from "react";
import {
  Table,
  TableContainer,
  Thead,
  Tbody,
  Tr,
  Th,
  Td,
  Skeleton,
  Button,
  Flex,
  Icon,
  Image,
  Avatar,
} from "@chakra-ui/react";
import { Link } from "@inertiajs/react";
import { EyeIcon, PencilSquareIcon } from "@heroicons/react/16/solid";
import DeleteButton from "./DeleteButton";

const DataTable = ({ columns, data, isLoading, calculateIndex }) => {
  return (
    <TableContainer>
      <Table variant="striped" colorScheme="gray" width="full">
        <Thead>
          <Tr borderBottom={"2px"} borderColor={"green.700"} bg={"green.700"}>
            {columns.map((column, index) => (
              <Th
                key={index}
                fontWeight="extrabold"
                fontSize="md"
                color="white"
                w={column.width || "auto"}
              >
                {column.header}
              </Th>
            ))}
          </Tr>
        </Thead>
        <Tbody verticalAlign={"top"}>
          {isLoading ? (
            <Tr>
              <Td colSpan={columns.length} textAlign="center">
                <Skeleton bg={"green.700"} height="20px" width="100%" />
              </Td>
            </Tr>
          ) : data.length === 0 ? (
            <Tr>
              <Td colSpan={columns.length} textAlign="center">
                Data Tidak Ditemukan
              </Td>
            </Tr>
          ) : (
            data.map((row, index) => (
              <Tr key={row.id} borderBottomWidth="1px">
                {columns.map((column, colIndex) => (
                  <Td key={colIndex}>
                    {column.accessor === "Aksi" ? (
                      <Flex>
                        {column.show ? (
                          <Button
                            colorScheme="green"
                            size="sm"
                            mr={2}
                            as={Link}
                            href={`${column.uri}/${row.id}`}
                          >
                            <Icon as={EyeIcon} mr={2} />
                            Detail
                          </Button>
                        ) : null}
                        <Button
                          colorScheme="yellow"
                          size="sm"
                          mr={2}
                          as={Link}
                          href={`${column.uri}/${row.id}/edit`}
                        >
                          <Icon as={PencilSquareIcon} mr={2} />
                          Edit
                        </Button>
                        <DeleteButton uri={`${column.uri}/${row.id}`} />
                      </Flex>
                    ) : column.accessor ? (
                      column.accessor.includes(".") ? (
                        column.accessor.split(".").reduce((o, i) => o[i], row)
                      ) : (
                        row[column.accessor]
                      )
                    ) : (
                      calculateIndex(index)
                    )}
                  </Td>
                ))}
              </Tr>
            ))
          )}
        </Tbody>
      </Table>
    </TableContainer>
  );
};

export default DataTable;
